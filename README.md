# RipperServer
### A Web-Wrapper for youtube-dl

RipperServer is a simple Web-Wrapper based on PHP.

I wrote RipperServer for use with https://github.com/Realitaetsverlust/RipperClient, but you can, also use it with your own software.

Requires PHP 8. I'm not sure if I used any PHP 8 features, but I only code on PHP 8 and only provide support for it.

### Installation

- Clone this repository
- Write your desired API-Key to api.txt
- Enable mod_rewrite mod_headers in your apache config
- You're ready now!

The ripper url looks like this:

https://example.com/Task?param=value

For initiating a youtube-dl request:

https://example.com/Ripper?videoId=VIDEO_ID&name=VIDEO_NAME&key=YOUR_API_KEY

This request will return a json that either looks something like this:

```json
{
  "videoTitle": "VIDEO_TITLE.mp3"
}
```

or, if something went wrong, like this:

```json
{
  "error": "error message"
}
```

If you want to trigger a download, the URL looks like this:

https://example.com/Download?videoTitle=VIDEO_TITLE

As title, you pass the name returned by the ripper request. You do not need an API-Key for the download. Because ... duh.