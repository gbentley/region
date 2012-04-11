<p>
{def $host=ezini( 'SiteSettings','SiteURL' )}
{def $countries=regions($access_type.name)}
{def $flagsrc=false()}
{"Change country"|i18n( 'extension/region' )}

{foreach $countries as $lang}
{if $lang.code|ne('*-*')}
    {set $flagsrc=concat( 'flags/',$lang.code,'.gif')|ezimage(no)}
{else}
    {set $flagsrc=concat( 'flags/unknown.gif')|ezimage(no)}
{/if}
  <a href="/{$lang.Siteaccess}/region/index/{$lang.Siteaccess}?URI={language_uri( $module_result.node_id, $lang.possible_languagecodes )}" title="{$lang.Name} {$lang.Language}">
  <img alt="{$lang.Name} {$lang.Language}" src="{$flagsrc}" />
  </a>
  {delimiter} {/delimiter}
{/foreach}
</p>