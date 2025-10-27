# YouTube API Quota Usage

**Daily Quota:** 18,900 units/day

## API Endpoint Costs
- `/search`: 100 units per request
- `/videos`: 1 unit per request (batch up to 50)
- `/channels`: 1 unit per request (batch up to 50)

## Scheduled Commands

| Command | Schedule | Endpoint | Daily Cost | % of Quota |
|---------|----------|----------|------------|------------|
| ImportChannelStreamsCommand | Every 2 hours | `/search` (11 channels) | ~13,200 units | 70% |
| UpdateUpcomingStreamsCommand | Hourly | `/videos` | ~24-480 units | 0.1-2.5% |
| CheckIfUpcomingStreamsAreLiveCommand | Every 5 min | `/videos` | ~288-576 units | 1.5-3% |
| CheckIfLiveStreamsHaveEndedCommand | Every 10 min | `/videos` | ~144-288 units | 0.8-1.5% |
| UpdateLiveAndFinishedStreamsCommand | Daily | `/videos` | ~1-2 units | <0.1% |
| UpdateChannelsCommand | Weekly | `/channels` | ~0.3 units | <0.1% |

**Total: ~13,657-14,546 units/day (72-77% of quota)**

## User-Triggered Actions
- Import channel: ~101 units (includes `/search`)
- Import/submit/approve stream: 1 unit each
