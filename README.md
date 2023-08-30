# Newspack ActiveCampaign

This plugin is a proof of concept that implements two integrations with ActiveCampaign:

- Support [ActiveCampaign's contact scoring system](https://help.activecampaign.com/hc/en-us/articles/221293927-Contact-Scoring-in-ActiveCampaign) as [Newspack Campaigns' segmentation criteria](https://help.newspack.com/engagement/campaigns/segmentation/)
- Push [reader data events](https://github.com/Automattic/newspack-plugin/blob/08e53ff639ffe3c58573b4f368f1db7285ff4055/includes/data-events/README.md) as [ActiveCampaign activity](https://help.activecampaign.com/hc/en-us/articles/221870128-An-overview-of-Event-Tracking#event-tracking-vs-site-tracking-0-1)

The push of reader data events allows Newspack's own events to trigger automations and score updates in ActiveCampaign.
