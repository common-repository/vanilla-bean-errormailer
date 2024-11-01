<?php

/* 
 * Copyright (C) 2014 Velvary Pty Ltd
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace VanillaBeans\ErrorMailer;
            // If this file is called directly, abort.
            if ( ! defined( 'WPINC' ) ) {
                    die;
            }


function RegisterSettings(){
     $em_settings = ['vbean_errormailer_recipients', 
         'vbean_errormailer_exemptions',
        'vbean_errormailer_subject',
        'vbean_errormailer_fatals',
         'vbean_errormailer_warnings',
         'vbean_errormailer_notices',
         'vbean_errormailer_parse',
         'vbean_errormailer_excludetypes',
         'vbean_errormailer_useslack',
         'vbean_errormailer_slackchannel',
         'vbean_errormailer_slackfrom',
         'vbean_errormailer_slackicon',
            'vbean_errormailer_lasterror',
            'vbean_errormailer_lasterrorcount',
            'vbean_errormailer_lasterrortime'];
     foreach($em_settings as $setting){
	register_setting( 'vbean-errormailer-settings', $setting );
     }
        
    
}

function SettingsPage(){
    ?>
<script language="javascript" type="text/javascript">
<?php
?>
</script>

<style>
    #vbexcludelist{
        position:relative;
        display:inline-table;
        width:100%;
        border: 1px groove;
        background-color: #f6c9cc;
        padding:5px;
    }
    .vbexcludelistitemcontainer{
        padding:5px;
        position:relative;
        display:inline-block;
        width:100%;
        margin-bottom:3px;
    }
    
    .vbexcludelistitempath{
        border-bottom:1px dashed;
        display:inline-block;
        width:70%;
    }
    
    .vbexcludelistitemline{
        border-bottom:1px dashed;
        display:inline-block;
        text-align:right;
        margin-right:7px;
        width:10%;
    }
    .vbexcludelistitemremove{
        text-align:right;
        display:inline-block;
        width:15%;
    }
    .vbcheading{
        display:inline-block;
        width:100%;
        font-weight: bold;
       background:#0000ff;
       color:white;
        padding:0;
        
    }
    .pixelplug{display:none;}
    .vbcheading div{
    } 
    .vbcheading div .vbexcludelistitempath{
        padding-left: 10px;
        margin:0;
    } 
    .vbcheading div .vbexcludelistitemline{
        margin:0;
    } 
    .vbcheading div .vbexcludelistitemremove{
        margin:0;
    } 
    
</style>

        <div class="wrap">
        <h2>Vanilla Bean Error Mailer Settings</h2>
            <form method="post" action="options.php">

    <?php settings_fields( 'vbean-errormailer-settings' ); ?>
    <?php do_settings_sections( 'vbean-errormailer-settings' ); ?>
                <table class="form-table">

                    <tr valign="top">
                        <th scope="row">Subject Line</th>
                        <td><input type="text" name="vbean_errormailer_subject" id="vbean_errormailer_subject" value="<?php echo \VanillaBeans\vbean_setting('vbean_errormailer_subject','Errormailer report: {siteurl} {errortype} on line {errorline} at {errorpage}')?>" style="width:600px;max-width:90%;">
                            <div class="description">The email subject line for your errors. You can use these placeholders: <span style="color:darkslateblue">{sitename} {siteurl} {errornumber} {errortype} {errorline} {errorpage}</span></div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Recipients</th>
                        <td><textarea cols="60" rows="3" name="vbean_errormailer_recipients" id="vbean_errormailer_recipients" placeholder="you@yourdomain.com"><?php echo \VanillaBeans\vbean_setting('vbean_errormailer_recipients','')?></textarea>
                            <div class="description">Comma separated list of email addresses that you would like error messages sent to. An empty field would result in no emails.</div>
                        </td>
                    </tr>


    
                    <tr valign="top">
                        <th scope="row">Slack Notification</th>
                        <td>
                            <div>
                                <input type="checkbox" class="checkbox" name="vbean_errormailer_useslack"  id="vbean_errormailer_useslack" value="1" <?php echo checked(1, get_option('vbean_errormailer_useslack'), false)   ?>/>&nbsp;Use Slack<br />
                                <label for ="vbean_errormailer_slackchannel">Channel </label><input type="text" name="vbean_errormailer_slackchannel" id="vbean_errormailer_slackchannel" value="<?php echo \VanillaBeans\vbean_setting('vbean_errormailer_slackchannel','general')?>">  &nbsp;&nbsp;  <label for ="vbean_errormailer_slackfrom">User Name </label><input type="text" name="vbean_errormailer_slackfrom" id="vbean_errormailer_slackchannel" value="<?php echo \VanillaBeans\vbean_setting('vbean_errormailer_slackfrom','{siteurl}')?>">  &nbsp;&nbsp;  <label for ="vbean_errormailer_slackicon">icon </label><input type="text" name="vbean_errormailer_slackicon" id="vbean_errormailer_slackicon" value="<?php echo \VanillaBeans\vbean_setting('vbean_errormailer_slackicon',null)?>" placeholder="empty 4 default">
                                
                            </div>
                            <?php 
                            $slackvalid = get_option('vbean_slack_hooker_setupvalid');
                            $useslack = get_option('vbean_errormailer_useslack');
        if (empty($slackvalid) && $useslack==true){
            echo '<div class="error" style="display:inline-block;">Your Slack configuration is not valid, or you do not have the Slack-Bot Vanilla Bean installed.</div>';
        }
?>
                            
                            
                        </td>
                    </tr>
    
    
    




                    <tr valign="top">
                        <th scope="row">Alerts for</th>
                        <td><input type="checkbox" class="checkbox" name="vbean_errormailer_fatals"  id="vbean_errormailer_fatals" value="1" <?php echo checked(1, get_option('vbean_errormailer_fatals'), false)   ?>/>&nbsp;Fatal Errors, 
                &nbsp;&nbsp;&nbsp;<input type="checkbox" class="checkbox" name="vbean_errormailer_warnings"  id="vbean_errormailer_warnings" value="1" <?php echo checked(1, get_option('vbean_errormailer_warnings'), false)   ?>/>&nbsp;Warnings,
                &nbsp;&nbsp;&nbsp;<input type="checkbox" class="checkbox" name="vbean_errormailer_notices"  id="vbean_errormailer_notices" value="1" <?php echo checked(1, get_option('vbean_errormailer_notices'), false)   ?>/>&nbsp;Notices,
                &nbsp;&nbsp;&nbsp;<input type="checkbox" class="checkbox" name="vbean_errormailer_parse"  id="vbean_errormailer_parse" value="1" <?php echo checked(1, get_option('vbean_errormailer_parse'), false)   ?>/>&nbsp;Parse Errors
                            <div class="description">Check the types of errors you would like to receive emails for.</div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><h3>Exclusions</h3>
                            By Error Types (advanced)</th>
                        <td><textarea cols="60" rows="2" name="vbean_errormailer_excludetypes" id="vbean_errormailer_excludetypes"><?php echo \VanillaBeans\vbean_setting('vbean_errormailer_excludetypes','')?></textarea>
                            <div class="description">Comma separated list of error type numbers that you want ignored. This is for uncaught error types.</div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">By File / line number</th>
                        <td>
                            <div class="description">Add error files with line numbers that you would like excluded from triggering emails. Line number is optional. If no line number is specified, entire file or error message will be used.</div>
                            Path:&nbsp;<input type="text" name="vbeanexcludepath" id="vbeanexcludepath" style="width:60%;"> Line&nbsp;Number:&nbsp;<input type="number" id="vbeanexcludelinenumber" name="vbeanexcludelinenumber" style="width:80px;"><button id="vbean_addExclusionbutton">Add</button> 
                            <div id="vbexcludelist">
                                
                                
                            </div>
                            <textarea cols="60" rows="3" style="display:none;" name="vbean_errormailer_exemptions" id="vbean_errormailer_exemptions"><?php echo \VanillaBeans\vbean_setting('vbean_errormailer_exemptions','')?></textarea>
                        
                        </td>
                    </tr>
                </table>



                

            <?php submit_button(); ?>
            </form>
        </div>    
<span class="pixelplug" style="display:none;"><img src="" width="1" height="1" id="errormailerpixel"></span>
<script language="javascript" type="text/javascript">
    var $v = jQuery.noConflict();
    var vbean_files=[];
    var vbean_excludes = new Array();
    
    
    <?php
    $path =get_home_path();
    $files = \VanillaBeans\vbean_ListPhp($path);
    $jsonfiles = json_encode($files);
    if(count($files)>-1){
        ?>
        $v(document).ready(function(){

                $v('#vbean_addExclusionbutton').on('click touchend',function(e){
                    e.preventDefault();  
                    var path = ''+$v('#vbeanexcludepath').val();
                    var linenumber = ''+$v('#vbeanexcludelinenumber').val();
                    if(!vbexists(path, linenumber)){
                        var indx = vbean_excludes.length;
                        vbean_excludes[indx]=new Array(path, linenumber, indx);
                        vbappendexclude(path,linenumber,indx);
                        vbpopulateexcludefield();
                        $v('#vbeanexcludepath').val('');
                        $v('#vbeanexcludelinenumber').val('');
                    }
                });
                vbreadexcludelist();
                vbrenderexludelist();
                vbean_listenremove();
        });

            
            
            vbean_files=<?php printf($jsonfiles); ?>;
            
    function vbrenderexludelist(){

        var s='';
            s+='<div class="vbcheading">';
            s+='<div class="vbexcludelistitempath">';
            s+= 'Directory Path or File Path, or error message';
            s+='</div>';
            s+='<div class="vbexcludelistitemline">';
            s+= 'Line';
            s+='</div>';
            s+='<div class="vbexcludelistitemremove">';
            s+='Remove';
            s+='</div>';
            s+='</div>';
            $v("#vbexcludelist").html(s);
        
        for(i=0;i<vbean_excludes.length;i++){
            vbappendexclude(vbean_excludes[i][0], vbean_excludes[i][1],i);
        }
            vbean_listenremove();
    }        
          
    function vbreadexcludelist(){
        vbean_excludes = new Array();
        var theval = ''+$v('#vbean_errormailer_exemptions').val();
        
        var exclusions=theval.split('\n');
        for(i=0;i<exclusions.length;i++){
            thisrow = exclusions[i].split(',');
            if(thisrow.length>1){
                vbean_excludes[vbean_excludes.length]=thisrow;
            }
        }
    }


    function vbpopulateexcludefield(){
        s='';
        for(i=0;i<vbean_excludes.length;i++){
            s+=vbean_excludes[i][0]+','+vbean_excludes[i][1]+'\n';
        }
        console.log(s);
        $v('#vbean_errormailer_exemptions').val(s);
    }


    function vbappendexclude(p,ln,idx){
            var listholder = $v("#vbexcludelist");
            var s='';
            s+='<div class="vbexcludelistitemcontainer">';
            s+='<div class="vbexcludelistitempath">';
            s+= p;
            s+='</div>';
            s+='<div class="vbexcludelistitemline">';
            s+= ln;
            s+='</div>';
            s+='<div class="vbexcludelistitemremove">';
            s+='<button data-index="'+idx+'" id="vbexcludebutton'+idx+'" class="vbexcludelistitemremovebutton">Remove</button>';
            s+='</div>';
            s+='</div>';
            $v(listholder).append(s);
            vbean_listenremove();
    }      
    
    function vbean_listenremove(){
        $v(".vbexcludelistitemremovebutton").each(function(){
            var isbound=true;
            var data= $v(this).data('events');
            console.log(data);
            if (data === undefined || data.length === 0) {
                isbound=false;
            }else{
               if(!$v.inArray('click',data)){
                   isbound=false;
                }
            }
            
            if(!isbound){
                $v(this).on('click touchend',function(e){
                    vbean_excludes.splice($v(this).data('index'), 1);
                    vbpopulateexcludefield();
                    vbrenderexludelist();
                    e.preventDefault();
                });
            }
        });
    }
    
    
    function vbean_isBound(ob,eventtype) {
        var data = $v(ob).data('events')[type];
        if (data === undefined || data.length === 0) {
            return false;
        }
        return (-1 !== $v.inArray(fn, data));
    }    
    
    
    
    
    function vbexists(p,ln){
        var exists=false;
        for(i=0;i<vbean_excludes.length;i++){
            if(vbean_excludes[i][0]==p&&vbean_excludes[i][1]==ln){
                return true;
            }
        }
        return exists;
    }
            
            
           <?php
    }
    
    ?> </script><?php
}



