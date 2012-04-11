<div class="block">
<div class="left">
{if $nocookie} 
    <p>Please enable <a href="http://en.wikipedia.org/wiki/HTTP_cookie">cookies</a>.</p>
{else}
<form action={"/region/index/"|ezurl} method="post">
<div class="element">
<label class="no-break" for="region">{'Please select your region and language'|i18n( 'region/index' )}</label>
</div>
<div class="element">
									<select id="region" name="region" size="1">
									{if $preferred_regions}
									{foreach $preferred_regions as $key => $region}
									   <option value="{$key}">{$region.Name}{if $region.Language} ({$region.Language}){/if}</option>
									{/foreach}
									   <option value="">----- All regions -----</option>
									{/if}
									{foreach $regions as $key => $region}
									   <option value="{$key}">{$region.Name}{if $region.Language} ({$region.Language}){/if}</option>
									{/foreach}
									</select>
</div>
<div class="element">
<input type="submit" class="defaultbutton" value="{'Go'|i18n( 'region/index' )}" />
</div>
<input name="URL" type="hidden" value="{$URL}" />
</form>
{/if}
</div>
</div>
<div class="break"></div>