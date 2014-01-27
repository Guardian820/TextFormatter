## Synopsis

This plugin allows the user to embed content from allowed sites using a `[media]` BBCode, site-specific BBCodes such as `[youtube]`, or from simply posting a supported URL in plain text.

It is designed to be able to parse any of the following forms:

 * `[media]http://www.youtube.com/watch?v=-cEzsCAzTak[/media]` *(simplest form)*
 * `[media=youtube]-cEzsCAzTak[/media]` *(from [XenForo's BB Code Media Sites](http://xenforo.com/help/bb-code-media-sites/))*
 * `[youtube]http://youtu.be/watch?v=-cEzsCAzTak[/youtube]` *(from various forum softwares such as [phpBB](https://www.phpbb.com/customise/db/bbcode/youtube/))*
 * `[youtube=http://www.youtube.com/watch?v=-cEzsCAzTak]` *(from [WordPress's YouTube short code](http://en.support.wordpress.com/videos/youtube/))*
 * `[youtube]-cEzsCAzTak[/youtube]` *(from various forum softwares such as [vBulletin](http://www.vbulletin.com/forum/forum/vbulletin-3-8/vbulletin-3-8-questions-problems-and-troubleshooting/vbulletin-quick-tips-and-customizations/204206-how-to-make-a-youtube-bb-code))*
 * `http://www.youtube.com/watch?v=-cEzsCAzTak` *(plain URLs are turned into embedded content)*

Has built-in support for Dailymotion, Facebook, LiveLeak, Twitch, YouTube [and more](https://github.com/s9e/TextFormatter/tree/master/src/Plugins/MediaEmbed/Configurator/sites.xml).

## Example

```php
$configurator = new s9e\TextFormatter\Configurator;

$configurator->MediaEmbed->add('dailymotion');
$configurator->MediaEmbed->add('facebook');
$configurator->MediaEmbed->add('youtube');

// Get an instance of the parser and the renderer
extract($configurator->finalize());

$examples = [
	'[media]http://www.dailymotion.com/video/x222z1[/media]',
	'https://www.facebook.com/photo.php?v=10100658170103643&set=vb.20531316728&type=3&theater',
	'[youtube]-cEzsCAzTak[/youtube]'
];

foreach ($examples as $text)
{
	$xml  = $parser->parse($text);
	$html = $renderer->render($xml);

	echo $html, "\n";
}
```
```html
<iframe width="560" height="315" src="//www.dailymotion.com/embed/video/x222z1" allowfullscreen="" frameborder="0" scrolling="no"></iframe>
<iframe width="560" height="315" src="https://www.facebook.com/video/embed?video_id=10100658170103643" allowfullscreen="" frameborder="0" scrolling="no"></iframe>
<iframe width="560" height="315" allowfullscreen="" frameborder="0" scrolling="no" src="//www.youtube.com/embed/-cEzsCAzTak"></iframe>
```

### Configure a site manually

In addition to the sites that are directly available by name, you can define new, custom sites. More examples are available in the [Cookbook](https://github.com/s9e/TextFormatter/blob/master/docs/Cookbook/README.md).

```php
$configurator = new s9e\TextFormatter\Configurator;

$configurator->MediaEmbed->add(
	'youtube',
	[
		'host'    => 'youtube.com',
		'extract' => "!youtube\\.com/watch\\?v=(?'id'[-0-9A-Z_a-z]+)!",
		'iframe'  => [
			'width'  => 560,
			'height' => 315,
			'src'    => 'http://www.youtube.com/embed/{@id}'
		]
	]
);

// Get an instance of the parser and the renderer
extract($configurator->finalize());

$text = '[youtube]http://www.youtube.com/watch?v=-cEzsCAzTak[/youtube]';
$xml  = $parser->parse($text);
$html = $renderer->render($xml);

echo $html;
```
```html
<iframe width="560" height="315" src="http://www.youtube.com/embed/-cEzsCAzTak" allowfullscreen="" frameborder="0" scrolling="no"></iframe>
```

### More examples

You can find more examples [in the Cookbook](https://github.com/s9e/TextFormatter/tree/master/docs/Cookbook#plugins).

### Supported sites

<table>
	<tr>
		<th>Id</th>
		<th>Site</th>
		<th>Example URLs</th>
	</tr>
	<tr>
		<td><code>bandcamp</code></td>
		<td>Bandcamp</td>
		<td>http://proleter.bandcamp.com/album/curses-from-past-times-ep<br/>http://proleter.bandcamp.com/track/april-showers<br/>http://therunons.bandcamp.com/track/still-feel</td>
	</tr>
	<tr>
		<td><code>blip</code></td>
		<td>Blip</td>
		<td>http://blip.tv/blip-on-blip/damian-bruno-and-vinyl-rewind-blip-on-blip-58-5226104<br/>http://blip.tv/play/g6VTgpjxbQA</td>
	</tr>
	<tr>
		<td><code>break</code></td>
		<td>Break</td>
		<td>http://www.break.com/video/video-game-playing-frog-wants-more-2278131</td>
	</tr>
	<tr>
		<td><code>cbsnews</code></td>
		<td>CBS News Video</td>
		<td>http://www.cbsnews.com/video/watch/?id=50156501n</td>
	</tr>
	<tr>
		<td><code>cnn</code></td>
		<td>CNN</td>
		<td>http://edition.cnn.com/video/data/2.0/video/showbiz/2013/10/25/spc-preview-savages-stephen-king-thor.cnn.html<br/>http://us.cnn.com/video/data/2.0/video/bestoftv/2013/10/23/vo-nr-prince-george-christening-arrival.cnn.html</td>
	</tr>
	<tr>
		<td><code>colbertnation</code></td>
		<td>Colbert Nation</td>
		<td>http://www.colbertnation.com/the-colbert-report-videos/429637/october-14-2013/5-x-five---colbert-moments--under-the-desk<br/>http://www.colbertnation.com/the-colbert-report-collections/429799/sorry--technical-difficulties/</td>
	</tr>
	<tr>
		<td><code>collegehumor</code></td>
		<td>CollegeHumor</td>
		<td>http://www.collegehumor.com/video/1181601/more-than-friends</td>
	</tr>
	<tr>
		<td><code>comedycentral</code></td>
		<td>Comedy Central</td>
		<td>http://www.comedycentral.com/video-clips/uu5qz4/key-and-peele-dueling-hats</td>
	</tr>
	<tr>
		<td><code>dailymotion</code></td>
		<td>Dailymotion</td>
		<td>http://www.dailymotion.com/video/x222z1<br/>http://www.dailymotion.com/user/Dailymotion/2#video=x222z1</td>
	</tr>
	<tr>
		<td><code>dailyshow</code></td>
		<td>The Daily Show with Jon Stewart</td>
		<td>http://www.thedailyshow.com/watch/mon-july-16-2012/louis-c-k-<br/>http://www.thedailyshow.com/collection/429537/shutstorm-2013/429508</td>
	</tr>
	<tr>
		<td><code>espn</code></td>
		<td>ESPN</td>
		<td>http://espn.go.com/video/clip?id=10315344<br/>http://espndeportes.espn.go.com/videohub/video/clipDeportes?id=deportes:2001302</td>
	</tr>
	<tr>
		<td><code>facebook</code></td>
		<td>Facebook</td>
		<td>https://www.facebook.com/photo.php?v=10100658170103643&amp;set=vb.20531316728&amp;type=3&amp;theater<br/>https://www.facebook.com/video/video.php?v=10150451523596807</td>
	</tr>
	<tr>
		<td><code>funnyordie</code></td>
		<td>Funny or Die</td>
		<td>http://www.funnyordie.com/videos/bf313bd8b4/murdock-with-keith-david</td>
	</tr>
	<tr>
		<td><code>gamespot</code></td>
		<td>Gamespot</td>
		<td>http://www.gamespot.com/destiny/videos/destiny-the-moon-trailer-6415176/<br/>http://www.gamespot.com/events/game-crib-tsm-snapdragon/gamecrib-extras-cooking-with-dan-dinh-6412922/<br/>http://www.gamespot.com/videos/beat-the-pros-pax-prime-2013/2300-6414307/</td>
	</tr>
	<tr>
		<td><code>gametrailers</code></td>
		<td>GameTrailers</td>
		<td>http://www.gametrailers.com/videos/jz8rt1/tom-clancy-s-the-division-vgx-2013--world-premiere-featurette-<br/>http://www.gametrailers.com/reviews/zalxz0/crimson-dragon-review<br/>http://www.gametrailers.com/full-episodes/zdzfok/pop-fiction-episode-40--jak-ii--sandover-village</td>
	</tr>
	<tr>
		<td><code>gfycat</code></td>
		<td>gfycat</td>
		<td>http://gfycat.com/SereneIllfatedCapybara</td>
	</tr>
	<tr>
		<td><code>gist</code></td>
		<td>GitHub Gist</td>
		<td>https://gist.github.com/s9e/6806305<br/>https://gist.github.com/6806305<br/>https://gist.github.com/s9e/6806305/ad88d904b082c8211afa040162402015aacb8599</td>
	</tr>
	<tr>
		<td><code>grooveshark</code></td>
		<td>Grooveshark</td>
		<td>http://grooveshark.com/playlist/Purity+Ring+Shrines/74854761<br/>http://grooveshark.com/#!/playlist/Purity+Ring+Shrines/74854761<br/>http://grooveshark.com/s/Soul+Below/4zGL7i?src=5<br/>http://grooveshark.com/#!/s/Soul+Below/4zGL7i?src=5</td>
	</tr>
	<tr>
		<td><code>hulu</code></td>
		<td>Hulu</td>
		<td>http://www.hulu.com/watch/484180</td>
	</tr>
	<tr>
		<td><code>ign</code></td>
		<td>IGN</td>
		<td>http://www.ign.com/videos/2013/07/12/pokemon-x-version-pokemon-y-version-battle-trailer</td>
	</tr>
	<tr>
		<td><code>indiegogo</code></td>
		<td>Indiegogo</td>
		<td>http://www.indiegogo.com/projects/gameheart-redesigned</td>
	</tr>
	<tr>
		<td><code>instagram</code></td>
		<td>Instagram</td>
		<td>http://instagram.com/p/gbGaIXBQbn/</td>
	</tr>
	<tr>
		<td><code>kickstarter</code></td>
		<td>Kickstarter</td>
		<td>http://www.kickstarter.com/projects/1869987317/wish-i-was-here-1<br/>http://www.kickstarter.com/projects/1869987317/wish-i-was-here-1/widget/card.html<br/>http://www.kickstarter.com/projects/1869987317/wish-i-was-here-1/widget/video.html</td>
	</tr>
	<tr>
		<td><code>liveleak</code></td>
		<td>LiveLeak</td>
		<td>http://www.liveleak.com/view?i=3dd_1366238099</td>
	</tr>
	<tr>
		<td><code>metacafe</code></td>
		<td>Metacafe</td>
		<td>http://www.metacafe.com/watch/10785282/chocolate_treasure_chest_epic_meal_time/</td>
	</tr>
	<tr>
		<td><code>rutube</code></td>
		<td>Rutube</td>
		<td>http://rutube.ru/video/b920dc58f1397f1761a226baae4d2f3b/<br/>http://rutube.ru/tracks/4118278.html?v=8b490a46447720d4ad74616f5de2affd</td>
	</tr>
	<tr>
		<td><code>slideshare</code></td>
		<td>SlideShare</td>
		<td>http://www.slideshare.net/Slideshare/how-23431564</td>
	</tr>
	<tr>
		<td><code>soundcloud</code></td>
		<td>SoundCloud</td>
		<td>http://api.soundcloud.com/tracks/98282116<br/>https://soundcloud.com/andrewbird/three-white-horses<br/>https://soundcloud.com/tenaciousd/sets/rize-of-the-fenix/</td>
	</tr>
	<tr>
		<td><code>spotify</code></td>
		<td>Spotify</td>
		<td>spotify:track:5JunxkcjfCYcY7xJ29tLai<br/>spotify:trackset:PREFEREDTITLE:5Z7ygHQo02SUrFmcgpwsKW,1x6ACsKV4UdWS2FMuPFUiT,4bi73jCM02fMpkI11Lqmfe<br/>http://open.spotify.com/user/ozmoetr/playlist/4yRrCWNhWOqWZx5lmFqZvt<br/>https://play.spotify.com/album/5OSzFvFAYuRh93WDNCTLEz</td>
	</tr>
	<tr>
		<td><code>strawpoll</code></td>
		<td>Straw Poll</td>
		<td>http://strawpoll.me/738091</td>
	</tr>
	<tr>
		<td><code>teamcoco</code></td>
		<td>Team Coco</td>
		<td>http://teamcoco.com/video/serious-jibber-jabber-a-scott-berg-full-episode<br/>http://teamcoco.com/video/73784/historian-a-scott-berg-serious-jibber-jabber-with-conan-obrien</td>
	</tr>
	<tr>
		<td><code>ted</code></td>
		<td>TED Talks</td>
		<td>http://www.ted.com/talks/eli_pariser_beware_online_filter_bubbles.html<br/>http://embed.ted.com/playlists/26/our_digital_lives.html</td>
	</tr>
	<tr>
		<td><code>traileraddict</code></td>
		<td>Trailer Addict</td>
		<td>http://www.traileraddict.com/trailer/watchmen/feature-trailer</td>
	</tr>
	<tr>
		<td><code>twitch</code></td>
		<td>Twitch</td>
		<td>http://www.twitch.tv/minigolf2000<br/>http://www.twitch.tv/minigolf2000/c/2475925<br/>http://www.twitch.tv/minigolf2000/b/361358487<br/>http://www.twitch.tv/m/57217</td>
	</tr>
	<tr>
		<td><code>ustream</code></td>
		<td>Ustream</td>
		<td>http://www.ustream.tv/channel/ps4-ustream-gameplay<br/>http://www.ustream.tv/baja1000tv<br/>http://www.ustream.tv/recorded/40688256</td>
	</tr>
	<tr>
		<td><code>vimeo</code></td>
		<td>Vimeo</td>
		<td>http://vimeo.com/67207222<br/>http://vimeo.com/channels/staffpicks/67207222</td>
	</tr>
	<tr>
		<td><code>vine</code></td>
		<td>Vine</td>
		<td>https://vine.co/v/bYwPIluIipH</td>
	</tr>
	<tr>
		<td><code>vk</code></td>
		<td>VK</td>
		<td>http://vk.com/video-7016284_163645555</td>
	</tr>
	<tr>
		<td><code>wshh</code></td>
		<td>WorldStarHipHop</td>
		<td>http://www.worldstarhiphop.com/videos/video.php?v=wshhZ8F22UtJ8sLHdja0<br/>http://m.worldstarhiphop.com/video.php?v=wshh2SXFFe7W14DqQx61</td>
	</tr>
	<tr>
		<td><code>youtube</code></td>
		<td>YouTube</td>
		<td>http://www.youtube.com/watch?v=-cEzsCAzTak<br/>http://youtu.be/-cEzsCAzTak<br/>http://www.youtube.com/watch?feature=player_detailpage&amp;v=jofNR_WkoCE#t=40<br/>http://www.youtube.com/watch?v=pC35x6iIPmo&amp;list=PLOU2XLYxmsIIxJrlMIY5vYXAFcO5g83gA</td>
	</tr>
</table>