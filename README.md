![larastreamers_social_small](https://user-images.githubusercontent.com/1394539/118348006-a0340000-b547-11eb-8359-1bb8649d9602.png)


# Larastreamers

This is the repository of [https://larastreamers.com](https://larastreamers.com/).

It shows you who is live coding next in the Laravel world.

## Installation Steps

> prerequisite: PHP > 8.2

* clone repository
* `composer install`

### Local installation

* Create DB `larastreamers`
* `composer install`
* `composer setup` (copies `env` file, generates key, and migrates DB)

### Laravel Sail

* copy `.env.example` to `.env`
* run `./vendor/bin/sail up -d`
* run `./vendor/bin/sail composer setup` (generates key, and migrates DB)

## Setup

In order to import videos from  YouTube you need:

* Fill `YOUTUBE_API_KEY` in your `.env` file
* Visit `/` and login with a user from `UserTableSeeder` or create your own one
* Import a stream by providing the video `id` 
* The video needs to be a scheduled live stream in the future

## Roadmap

Currently, there are no big updates planned.

## Contribute

We welcome everyone to contribute to this project. Just make sure to suggest bigger features first in the issues, before you spend a lot of time. Please also make sure to `write tests` for what you implement. I only merge PRs with tests :-)
