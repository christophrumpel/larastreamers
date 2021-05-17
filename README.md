![larastreamers_social_small](https://user-images.githubusercontent.com/1394539/118348006-a0340000-b547-11eb-8359-1bb8649d9602.png)


# Larastreamers

This is the repository of [https://larastreamers.com](https://larastreamers.com/).

It shows you who is live coding next in the Laravel world.

## Installation Steps

* clone repository
* Create DB `larastreamers`
* `composer install`
* `composer setup` (copies `env` file, generates key, and migrates DB)

## Setup

In order to import videos from  YouTube you need:

* Fill `YOUTUBE_API_KEY` in your `.env` file
* Visit `/` and login with a user from `UserTableSeeder` or create your own one
* Import a stream by providing the video `id` 
* The video needs to be a scheduled live stream in the future

## Roadmap

* [x] Backup DB (Laravel Backup)
* [x] RSS Feed
* [x] Better timezone support (using timezone through browser
* [x] Automatically check for updates on stored streams
* [x] Automatically fix styles on PRs (after PR is merged)
* [x] Add Twitter channel import to load its upcoming live streams
* [ ] Twitter integration (tweet when added, tweet when X minutes before)
* [ ] Calendar link for every event
* [ ] Approval flow
* [ ] User can make suggestions for streams to add
* [ ] Show when a stream is currently live
* [ ] Add calendar subscriptions

## Contribute

We welcome everyone to contribute to this project. Just make sure to suggest bigger features first, before you spend a lot of time. Please also make sure to `write tests` for what you implement. I only merge PRs with tests :-)
