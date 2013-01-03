<div class="z-formrow">
    <label for='shoutit_nbMsg'>{gt text='Number of recent messages displayed'}</label>
    <input id='shoutit_nbMsg' type='text' name='shoutit_nbMsg' maxlength='3' value={$vars.nbMsg|safetext} />
    <em class="z-formnote z-sub">{gt text="Default is 100 messages, minimum is 10."}</em>
</div>

<div class="z-formrow">
    <label for='shoutit_refRate'>{gt text='Refresh rate in sec.'}</label>
    <input type='text' id='shoutit_refRate' mandatory='1' name='shoutit_refRate' maxLength='2' value={$vars.refRate|safetext} />
    <em class="z-formnote z-sub">{gt text="Common value for all Shoutit block instances.<br />Default is 8, minimum is 6"}</em>
</div>

<div class="z-formrow">
    <label for='shoutit_msgLength'>{gt text='Message length'}</label>
    <input id='shoutit_msgLength' type='text' name='shoutit_msgLength' maxLength='3' value={$vars.msgLength|safetext} />
    <em class="z-formnote z-sub">{gt text="Number of characters available per message.<br />Default is 70, minimum is 20"}</em>
</div>

<div class="z-formrow">
    <label for='shoutit_grpMsg'>{gt text='Owner and group messages'}</label>
    {if $vars.grpMsg|default:0 eq 1}
    <input id='shoutit_grpMsg' type='checkbox' name='shoutit_grpMsg' value='1' checked="checked" />
    {else}
    <input id='shoutit_grpMsg' type='checkbox' name='shoutit_grpMsg' value='1' />
    {/if}
    <em class="z-formnote z-sub">{gt text='Only display user messages and messages from same user group(s).<br />Please set Shoutit permissions according your needs before (Read install.txt).'}</em>
</div>

<div class="z-formrow">
    <label for='shoutit_delMsg'>{gt text='Clear all messages?'}</label>
    <input id='shoutit_delMsg' type='checkbox' name='shoutit_delMsg' value='1' />
    <em class="z-sub z-warningmsg">{gt text='Tick the box, be careful when pressing \'Save\' this will delete all messages from the block.'}</em>
</div>