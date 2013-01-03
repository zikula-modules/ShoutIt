{* $Id$ *}
{ajaxheader modname='Shoutit' filename='Shoutit.js'}

 <div class="shoutit" id="shoutit_{$bid}">
	<div class="shoutit_contentwrap" id="shoutit_contentwrap_{$bid}">
		<div class="shoutit_content" id="shoutitcontent_{$bid}">
                    {gt text="Loading shoutbox"} ...
		</div>
	</div>
        {if $postPerm}
        <div class="shoutit_inputwrap" id="shoutitinput_{$bid}">
            <form class='z-form' action="" name="shoutitform_{$bid}" onsubmit="return false;">
                <strong>{gt text="Message"}</strong>
                <div class="shoutit_counterwrap">
                    <input readonly type="text" class="shoutit_counter" id="shoutitcounter_{$bid}" name="shoutitcounter_{$bid}" maxlength="3" value="{$msgLength}" />{gt text="characters left"}
                </div>
                <input type="text" class="shoutit_message" id="shoutitmessage_{$bid}" name="shoutitmessage_{$bid}" value="" onKeyDown="shoutit_{$bid}.textCounter()" onKeyUp="shoutit_{$bid}.textCounter()" /><br />
                {if $grpMsg}
                <strong>{gt text="@"}</strong><select id="shoutitgroup_{$bid}">
                    <option value="-">-</option>
                {foreach item=group from=$groups}
                    <option value="{$group.gid}">{$group.name}</option>
                {/foreach}
                </select>
                {/if}
                <input type="submit" class="z-bt-ok z-bt-small" name="shoutitsend_{$bid}" id="shoutitsend_{$bid}" value="{gt text="Send"}" />
                {* to activate pn_bbsmile for shoutit remove the stars in the following line *}
                {* ModUtil::func modname=bbsmile type=user func=bbsmiles textfieldid="shoutitmessage_`$modname``$bid`" *}
            </form>
        </div>
	{/if}
	<script type="text/javascript">
	// <![CDATA[
		var shoutit_{{$bid}} = new shoutit({{$bid}}, {{$modvars.Shoutit.shoutit_refresh_rate}}, {{$msgLength}}, {{$postPerm}}, {{$grpMsg}});
	// ]]>
	</script>
	{* pndebug *}
</div>