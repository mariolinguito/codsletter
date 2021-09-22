<?php

namespace App\Http\Controllers;

use App\Models\WebsiteFlashData;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Goutte;
use Auth;
use DB;

class GoutteController extends Controller
{

    private $sliced_nodes;
    private $all_titles;
    private $joined_data;

    public function __construct($user_id) {

        $joined_data = 
            DB::table('websites')
                ->join('settings', 'websites.id', '=', 'settings.website_id')
                ->join('website_flash_data', 'websites.id', '=', 'website_flash_data.website_id')
                ->where('websites.user_id', $user_id)
                ->first();

        if($joined_data === null) {
            $this->generateFlashData($user_id);
        } else {
            $this->joined_data = $joined_data;
        }
    }

    public function matchDaysNumber() {
        try {
            $current_time = Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now()->toDateTimeString());
            $days = Carbon::createFromFormat('Y-m-d H:s:i', $this->joined_data->last_run)->diffInDays($current_time);

            if($days >= $this->joined_data->scheduler) {
                return true;
            }
        } catch(\ErrorException $exception) {
            # error handler
        }

        return false;
    }

    public function checkPostsNumber() {
        $this->sliced_nodes = [];

        try {
            $crawler = Goutte::request('GET', $this->joined_data->url);
            $saved_node = $this->joined_data !== null ? $this->joined_data->last_title : env('LAST_TITLE_PLACEHOLDER');
            $current_last_node = $crawler->filter($this->joined_data->title_tag)->first()->text();
            $headline = $this->joined_data->headline;
            $email_object = $this->joined_data->email_object;

            if($this->isTitleDifferent($saved_node, $current_last_node)) {

                $all_nodes = $this->getAllTitles();

                if($this->matchPostsNumber($all_nodes, $saved_node, $current_last_node)) {
                    $this->sliced_nodes = array_slice($all_nodes, 0, $this->joined_data->posts_number);
                    $this->sliced_nodes['email_object'] = $email_object;
                    $this->sliced_nodes['headline'] = $headline;

                    $this->saveLastNode($this->joined_data->website_id, $current_last_node);
                }
            }
        } catch(\ErrorException $exception) {
            # error handler
        }

        return $this->sliced_nodes;
    }

    private function generateFlashData($user_id) {
        $current_time = Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now()->toDateTimeString());
        $website_id = DB::table('websites')->where('user_id', $user_id)->first();

        try {
            DB::table('website_flash_data')->insert([
                'last_title' => env('LAST_TITLE_PLACEHOLDER'),
                'last_run' => $current_time,
                'website_id' => $website_id->id,
            ]);
        } catch(\ErrorException $exception) {
            # error handler
        }
    }

    private function isTitleDifferent($saved_node, $current_last_node) {
        $is_different = false; 
        
        if($current_last_node !== $saved_node) {
            $is_different = true;
        }

        return $is_different;
    }

    private function matchPostsNumber($all_nodes, $saved_node, $current_last_node) {
        $matched = false;
        $saved_node_index = array_search($saved_node, array_column($all_nodes, 'title')); 
        $current_last_node_index = array_search($current_last_node, array_column($all_nodes, 'title'));

        if(
            abs($current_last_node_index - $saved_node_index) >= $this->joined_data->posts_number ||
            $saved_node === env('LAST_TITLE_PLACEHOLDER')
        ) {
            $matched = true;
        }

        return $matched;
    }

    private function saveLastNode($website_id, $last_node) {
        WebsiteFlashData::updateOrCreate(['website_id' => $website_id], [
            'last_title' => $last_node,
            'last_run' => Carbon::now()->toDateTimeString(),
        ]);
    }

    private function getAllTitles() {
        $this->all_titles = [];

        try {
            $crawler = Goutte::request('GET', $this->joined_data->url);
            $crawler->filter($this->joined_data->title_tag)->each(function($node) {
                $node_element['title'] = $node->text();
                $node_element['url'] = $this->joined_data->url . $node->attr('href');

                array_push($this->all_titles, $node_element);
            });
        } catch(\InvalidArgumentException $exception) {
            $this->all_titles = false;
        }

        return $this->all_titles;
    }
}