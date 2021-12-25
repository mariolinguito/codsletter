<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Models\WebsiteFlashData;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Goutte;
use Auth;

class GoutteController extends Controller
{

    private $website;

    private $settings;

    private $flash_data;

    private $sliced_nodes;

    private $all_titles;

    public function __construct($user_id) {

        $this->website = Website::where(
            ['user_id' => $user_id]
        )->first();

        $this->settings = $this->website->settings;

        $this->flash_data = $this->website->flash_data;

        if($this->flash_data === null) {
            $this->generateFlashData($user_id);
        }
    }

    public function matchDaysNumber() {
        try {
            $current_time = Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now()->toDateTimeString());
            $days = Carbon::createFromFormat('Y-m-d H:s:i', $this->flash_data->last_run)->diffInDays($current_time);

            if($days >= $this->settings->scheduler) {
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
            $crawler = Goutte::request('GET', $this->website->url);
            $saved_node = $this->website !== null ? $this->flash_data->last_title : env('LAST_TITLE_PLACEHOLDER');
            $current_last_node = $crawler->filter($this->settings->title_tag)->first()->text();
            $headline = $this->settings->headline;
            $email_object = $this->settings->email_object;

            if($this->isTitleDifferent($saved_node, $current_last_node)) {

                $all_nodes = $this->getAllTitles();

                if($this->matchPostsNumber($all_nodes, $saved_node, $current_last_node)) {
                    $this->sliced_nodes = array_slice($all_nodes, 0, $this->settings->posts_number);
                    $this->sliced_nodes['email_object'] = $email_object;
                    $this->sliced_nodes['headline'] = $headline;

                    $this->saveLastNode($this->settings->website_id, $current_last_node);
                }
            }
        } catch(\ErrorException $exception) {
            # error handler
        }

        return $this->sliced_nodes;
    }

    private function generateFlashData($user_id) {
        $current_time = Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now()->toDateTimeString());

        try {
            $flash_data = WebsiteFlashData::create([
                'last_title' => env('LAST_TITLE_PLACEHOLDER'),
                'last_run' => $current_time,
                'website_id' => $this->website->id,
            ]);
        } catch(\ErrorException $exception) {
            # Empty.
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
            abs($current_last_node_index - $saved_node_index) >= $this->settings->posts_number ||
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
            $crawler = Goutte::request('GET', $this->website->url);
            $crawler->filter($this->settings->title_tag)->each(function($node) {
                $node_element['title'] = $node->text();
                $node_element['url'] = $this->website->url . $node->attr('href');

                array_push($this->all_titles, $node_element);
            });
        } catch(\InvalidArgumentException $exception) {
            $this->all_titles = false;
        }

        return $this->all_titles;
    }
}