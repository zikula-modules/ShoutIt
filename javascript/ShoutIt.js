/**
 * ShoutIt module for Zikula Application Framework
 * @author       Gabriel Freinbichler
 *              refactored for zk 1.3 by Philippe Baudrion - UniGE/FTI
 */
 var shoutit = Class.create({
    initialize: function(bid, refRate, msgLength, postPerm, grpMsg) {
        this.bid = bid;
        this.refRate = refRate;
        this.msgLength = msgLength;
        this.postPerm  = postPerm;
        this.grpMsg    = grpMsg;

        this.getData();
       
        if (this.postPerm == 1) {
            $('shoutitsend_' + this.bid).observe('click', function(){this.saveData();}.bindAsEventListener(this));
            $('shoutitsend_' + this.bid).observe('keypress', function(){this.saveData();}.bindAsEventListener(this));
        }
    },

    getData: function() {

        this.updater = new Ajax.PeriodicalUpdater(
                          'shoutitcontent_' + this.bid,
                           Zikula.Config.baseURL + 'ajax.php?module=ShoutIt&func=getmessages',
                           {
                               method: 'post',
                               parameters: {bid: this.bid},
                               frequency: this.refRate
                           }
                       );
    },

    saveData: function() {
        this.updater.stop();

        if(this.grpMsg) {
            var e = $('shoutitgroup_' + this.bid)[$('shoutitgroup_' + this.bid).selectedIndex].value;
        }
        var m = $('shoutitmessage_' + this.bid).value;

        if (((this.grpMsg == '1' && e != '-') && m != '') ||
            (this.grpMsg == '0' && m != '')) {
            new Zikula.Ajax.Request(
                Zikula.Config.baseURL + "ajax.php?module=ShoutIt&func=savemessages",
                {
                    method: 'post',
                    parameters:
                    {
                        bid: this.bid,
                        gid: e,
                        message: m
                    },
                    onComplete: this.updater.start()
                }
            );

            // form reset
            $('shoutitmessage_' + this.bid).value = '';
            $('shoutitcounter_' + this.bid).value = this.msgLength;
            if(this.grpMsg) {
                $('shoutitgroup_' + this.bid).value = '-';
            }
        }
        else if (this.grpMsg == '1' && e == '-' || m == '') {
            alert("Please select a group and write a message before sending!");
        }
        else if (this.grpMsg == '0' && m == '') {
            alert("Please write a message before sending!");
        }

        $('shoutitmessage_' + this.bid).focus();
    },

    textCounter: function() {
        // if too long...trim it!
        if ($('shoutitmessage_' + this.bid).value.length > this.msgLength) {
            $('shoutitmessage_' + this.bid).value = $('shoutitmessage_' + this.bid).value.substring(0, this.msgLength);
            $('shoutitmessage_' + this.bid).setSelectionRange(this.msgLength, this.msgLength);
        // otherwise, update 'characters left' counter
        } else {
            $('shoutitcounter_' + this.bid).value = this.msgLength - $('shoutitmessage_' + this.bid).value.length;
        }
    }
});
