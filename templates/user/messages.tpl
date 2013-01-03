{if $messages|@count == '0'}
    {gt text="No messages are stored in the database."}
{/if}
{foreach item='message' from=$messages}
	<span class="si_name">
		{if $message.cr_uid == '0'}
			{gt text="Guest"}
		{else}
			{$message.uname}
		{/if}
		<span>
		{if $smarty.now|date_format:'%d.%m.%y' eq $message.cr_date|date_format:'%d.%m.%y'}
			{$message.cr_date|date_format:'%H:%M'}
		{else}
			{$message.cr_date|date_format:'%d.%m.%y, %H:%M'}
		{/if}
		</span>:
	</span>
	<span class="si_message">
		{* to deactivate transform hooks like bbsmile for shoutit, remove the stars in line 19 and add them in line 20 *}
		{* $message.message *}
		{$message.message|notifyfilters:'bbsmile.smilies'}
	</span>
        <br />
{/foreach}