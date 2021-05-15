![larastreamers_social_small](https://user-images.githubusercontent.com/1394539/118348006-a0340000-b547-11eb-8359-1bb8649d9602.png)


# Larastreamers

This is the repository of [https://larastreamers.com](https://larastreamers.com/).

It shows you who is live coding next in the Laravel world.

## Setup

In order to import videos from  YouTube you need:

* Run `composer install` then `composer setup`
* Fill `YOUTUBE_API_KEY` in your `.env` file
* The video needs to be in the future (at least today)
* The video needs to be a scheduled live stream

## Roadmap

* [x] Backup DB (Laravel Backup)
* [x] RSS Feed
* [x] Better timezone support (using timezone through browser
* [x] Automatically check for updates on stored streams
* [ ] Twitter integration (tweet when added, tweet when X minutes before)
* [ ] Calendar link
* [ ] Approval flow
* [ ] User can make suggestions for streams to add
* [ ] User can add event to cal
* [ ] User can subscribe to notifications (newsletter list?)
* [ ] Show when I stream is currently live

## Contribute

We welcome everyone to contribute to this project. Just make sure to suggest bigger features first, before you spend a lot of time.
