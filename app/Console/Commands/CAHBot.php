<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Discord\DiscordCommandClient;
use App\Cah;
use App\LolPlayer;
use App\FunnyjunkUser;
use App\User;
use Posttwo\FunnyJunk\User as FJUser;

class CAHBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cahbot:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the CAH suggestions bot';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->discord = new DiscordCommandClient([
            'token' => env('DISCORD_TOKEN'),
            'description' => "CAH Bot",
            'discordOptions' => [
                'disabledEvents' => [
                    'PRESENCE_UPDATE'
                ]
            ]
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->discord->registerCommand('white', function ($message) {
            return $this->addCard('white', $message);
        });
        $this->discord->registerCommand('black', function ($message) {
            return $this->addCard('black', $message);
        });
        $this->discord->registerCommand('coach', function ($message) {
            return $this->addPlayer('coach', $message);
        });
        $this->discord->registerCommand('noob', function ($message) {
            return $this->addPlayer('noob', $message);
        });
        $this->discord->registerCommand('mention', function($message) {
            return $this->getUser($message);
        });
        $this->discord->registerCommand('reverse', function($message) {
            return $this->getFJ($message);
        });
        $this->discord->registerCommand('stats', function($message) {
            return $this->getStats($message);
        });
        //mod shit
        $this->discord->registerCommand('fuck', function ($message) {
            return $this->shutUp($message);
        });
        $this->discord->run();
    }

    protected function getUser($message)
    {
        //return "Test Posttwo";
        //return "Test: " . $message->content;
        $split = explode(" ", $message->content);
        $username = $split[count($split)-1];
        $user = FunnyjunkUser::where('username', $username)->first();
        if($user == null)
        {
            return "sorry that user is a retard";
        }
        return " has mentioned <@" . $user->user->discord_id . ">";
    }

    protected function getFJ($message)
    {
        $mentions = $message->mentions;
        foreach($mentions as $mention)
        {
            if($mention->id == "247756747882758154") //skip bot
                continue;
            
            $user = User::where('discord_id', $mention->id)->first();
            if($user == null)
                return "couldnt find out who that fucker is, sorry. :(";
            $this->info($user->fjuser);
            return $mention->username . " => https://funnyjunk.com/u/" . $user->fjuser->username;
        }
    }

    protected function getStats($message)
    {
        $split = explode(" ", $message->content);
        $username = $split[count($split)-1];
        $user = new FJUser();
        $user->set(array('username' => $username));
        $user->populate();
        $return = "looked up " . $user->username;
        $return = $return . "\n" . "ID: " . $user->id;
        $return = $return . "\n" . "Registered: " . $user->date_registered;
        $return = $return . "\n" . "Role: " . $user->role_name;
        $return = $return . "\n" . "Group: " . $user->group_name;
        $return = $return . "\n" . "Avatar: " . $user->big_avatar;
        return $return;
    }

    protected function addCard($color, $message)
    {
        if($message->channel_id == 307268778310238208){
            $explode = explode('|', $message->content);
            $text = $explode[1];
            $cah = new Cah;
            $cah->color = $color;
            $cah->text = $text;
            $cah->discord_id = $message->author->id;
            $cah->save();
            $this->info("Added " . $color . " card ID: " . $cah->id);
            return "Added " . $color . " card:```" . $text . "```";
        }
    }

    protected function addPlayer($type, $message)
    {
        if($message->channel_id == 307269832355741696){
            $explode = explode('|', $message->content);
            $text = $explode[1];
            $cah = new LolPlayer;
            $cah->type = $type;
            $cah->times = $text;
            $cah->discord_id = $message->author->id;
            $cah->save();
            $this->info("Added " . $type . " player: " . $cah->id);
            return "Added " . $type . " player with timezone:```" . $text . "```";
        }
    }

    protected function shutUp($message)
    {
        if($message->channel_id == 334673571009789953){
            //Put into cache
            \Cache::forever("Cron-Ratings-Silence", 1);
            return "Skipping next bot run.";
        }
    }
}
