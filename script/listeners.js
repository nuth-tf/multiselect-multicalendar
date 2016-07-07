
$(document).ready(function () {

    $('#user_filter_selectbox').change(function() {
        
        if($(this).val() > 0 && MyCalendar.loggedInUser !== undefined && MyCalendar.loggedInUser !== 1000000) {
            $('#reset_user_filter_btn').show();
            var old_user_id = MyCalendar.filterUser;
            MyCalendar.filterUser = $(this).val();
            
            if(MyCalendar.filterUser === '') {
                MyCalendar.openCalendar('all');
            } else { 
                MyCalendar.filterByUser(old_user_id);
            }
        }
    });
    
    $('#reset_user_filter_btn').click(function() {
        $('#reset_user_filter_btn').hide();
        
        var old_user_id = $('#user_filter_selectbox').val();
        MyCalendar.filterUser = '';
        $('#user_filter_selectbox').val('');
        MyCalendar.filterByUser(old_user_id);
    });
                
    $('#recurring_checkbox').click(function (t) {
        if (t.currentTarget.checked == false) {
            $('#interval_container').hide();
            $('#interval_day_choice_div').hide();
            $('#interval_week_choice_div').hide();
            $('#interval_month_choice_div').hide();
            $('#interval_year_choice_div').hide();
            $('#info_txt').html('');
        } else {
            $('#interval_container').show();
            $('#interval_day_choice_div').show();
            $('#interval_div').val('daily');
        }

    });

    $('#edited_title').blur(function () {
        if ($('#edited_title').val() !== '') {
            $('#edited_title').removeClass('error_color');
        }
    });

    $('#edited_description').blur(function () {
        if ($('#edited_description').val() !== '') {
            $('#edited_description').removeClass('error_color');
        }
    });

    $('#edited_location').blur(function () {
        if ($('#edited_location').val() !== '') {
            $('#edited_location').removeClass('error_color');
        }
    });

    $('#edited_phone').blur(function () {
        if ($('#edited_phone').val() !== '') {
            $('#edited_phone').removeClass('error_color');
        }
    });

    $('#edited_myurl').blur(function () {
        if ($('#edited_myurl').val() !== '') {
            $('#edited_myurl').removeClass('error_color');
        }
    });

    $('#allday_checkbox').click(function (t) {
        var now = new Date();
        if (t.currentTarget.checked == false) {
            $('#timepicker_starttime').removeAttr('disabled');
            $('#timepicker_endtime').removeAttr('disabled');
            $('#timepicker_starttime').show();
            $('#timepicker_endtime').show();

            if (MyCalendar.defaultTimeForEvent !== '') {
                var startdate = new Date(now.format('mm/dd/yyyy') + ' ' + MyCalendar.defaultTimeForEvent + ':00');
                var enddate = new Date(now.format('mm/dd/yyyy') + ' ' + MyCalendar.defaultTimeForEvent + ':00');

                if (MyCalendar.showAMPM) {
                    if (MyCalendar.editdialogTimepickerType == 'simple') {
                        $('#timepicker_starttime').val(dateFormat(startdate,'hh:00 TT'));
                        $('#timepicker_endtime').val(dateFormat(enddate,'hh:00 TT'));
                    } else {
                        $('#timepicker_starttime').timepicker('setTime', dateFormat(startdate, 'hh:00 TT'));
                        $('#timepicker_endtime').timepicker('setTime', dateFormat(enddate, 'hh:00 TT'));
                    }
                  
                } else {
                    if (MyCalendar.editdialogTimepickerType == 'simple') {
                        $('#timepicker_starttime').val(MyCalendar.defaultTimeForEvent);
                        $('#timepicker_endtime').val(MyCalendar.defaultTimeForEvent);
                    } else {
                        $('#timepicker_starttime').timepicker('setTime', MyCalendar.defaultTimeForEvent);
                        $('#timepicker_endtime').timepicker('setTime', MyCalendar.defaultTimeForEvent);
                    }
                    
                }
            } else {
                if (MyCalendar.showAMPM) {
                    if (MyCalendar.editdialogTimepickerType == 'simple') {
                        $('#timepicker_starttime').val(dateFormat(now,'hh:00 TT'));
                        $('#timepicker_endtime').val(dateFormat(now,'hh:00 TT'));
                    } else {
                        $('#timepicker_starttime').timepicker('setTime', dateFormat(now, 'hh:00 TT'));
                        $('#timepicker_endtime').timepicker('setTime', dateFormat(now, 'hh:00 TT'));
                    }
                 
                } else {
                    if (MyCalendar.editdialogTimepickerType == 'simple') {
                        $('#timepicker_starttime').val(dateFormat(now, 'HH:00'));
                        $('#timepicker_endtime').val(dateFormat(now, 'HH:00'));
                    } else {
                        $('#timepicker_starttime').timepicker('setTime', dateFormat(now, 'HH:00'));
                        $('#timepicker_endtime').timepicker('setTime', dateFormat(now, 'HH:00'));
                    }
                    
                }

            }

        } else {
            //$('#timepicker_starttime').attr('disabled', 'disabled');
            //$('#timepicker_endtime').attr('disabled', 'disabled');
            $('#timepicker_starttime').hide();
            $('#timepicker_endtime').hide();

            if (MyCalendar.showAMPM) {
                if (MyCalendar.editdialogTimepickerType == 'simple') {
                    $('#timepicker_starttime').val('11:00');
                    $('#timepicker_endtime').val('11:00');
                } else {
                    $('#timepicker_starttime').timepicker('setTime', '11:00 AM');
                    $('#timepicker_endtime').timepicker('setTime', '11:00 AM');
                }

            } else {
                if (MyCalendar.editdialogTimepickerType == 'simple') {
                    $('#timepicker_starttime').val('11:00');
                    $('#timepicker_endtime').val('11:00');
                } else {
                    $('#timepicker_starttime').timepicker('setTime', '11:00 AM');
                    $('#timepicker_endtime').timepicker('setTime', '11:00 AM');
                }

            }
            
        }
    });

    var showMessage = function (message, type) {
        if (type === undefined) {
            type = 'warning';
        }
        //$('#cal_message').css('background-color', 'red');
        $('#cal_message').removeClass('warning');
        $('#cal_message').removeClass('error');
        $('#cal_message').removeClass('success');
        $('#cal_message').addClass(type);
        $('#cal_message').html('<span style="font-weight:bold;text-transform:uppercase;">' + type + ': </span>' + message);
        $('#cal_message').show();

        setTimeout(function () {
            $('#cal_message').html('');
            $('#cal_message').hide();
        }, 3000);
    };

    var saveProfile = function () {
        var lastname = $('#profile_lastname').val();
        var firstname = $('#profile_firstname').val();
        var infix = $('#profile_infix').val();
        var country = $('#profile_country').val();
        var username = $('#profile_username').val();
        var email = $('#profile_email').val();
        var birthdate_day = $('#profile_birthdate_day').val();
        var birthdate_month = $('#profile_birthdate_month').val();
        var birthdate_year = $('#profile_birthdate_year').val();
        var password = $('#profile_password').val();
        var confirm = $('#profile_confirm').val();

        if (username !== null && username !== '' && email !== null && email !== '' && lastname !== null && lastname !== '') {

            if (password !== null && password !== '' && confirm !== null && confirm !== '') {
                if (password !== confirm) {
                    $('#profile_error_message').html('Passwords do not match');
                }
            }
            var data = {
                email: email,
                firstname: firstname,
                username: username,
                lastname: lastname,
                infix: infix,
                country: country,
                birthdate_day: birthdate_day,
                birthdate_month: birthdate_month,
                birthdate_year: birthdate_year,
                password: password,
                confirm: confirm
            };

            $.ajax({
                type: "POST",
                url: MyCalendar.FULLCAL_URL + '/index.php?action=save_profile',
                data: data,
                dataType: 'json',
                success: function (result) {
                    if (result.success) {
                        $("#dialog-profile").dialog("close");
                        showMessage('Profile saved successfully', 'success');

                    } else {
                        if (result.notloggedin) {
                            alert(Lang.Alert.NotLoggedIn);
                            window.location = MyCalendar.FULLCAL_URL;
                        } else {
                            $('#profile_error_message').html(result.error);
                        }
                    }
                }
            });
        } else {
            $('#profile_error_message').html('Name, username and email are required');
        }
    };



    $('#add_groupuserfield').click(function (t) {
        //$( "#dialog:ui-dialog" ).dialog( "destroy" );

        $("#select_user").dialog({
            modal: true,
            title: 'Add/delete users',
            height: 350,
            width: 440,
            closable: false,
            resizable: false,
            buttons: [{
                    html: Lang.Popup.saveButtonText,
                    click: function (a, b) {
                        var values = $(".multiselect").multiselect('selectedValues');

                        $('#calendar_groupusers').val(values);

                        $('#no_users_found').hide();
                        $('#added_users').html('<br />Save this group to add the ' + (values.length - MyCalendar.cntGroupUsers) + ' added user(s).');
                        $(this).dialog("close");
                    }
                }, {
                    html: Lang.Popup.closeButtonText,
                    click: function () {
                        $(this).dialog("close");
                    }
                }]
        });
        //$('.multiselect').bind('multiselectselected', function(event, options) {
        // ui.option is the DOMOption node of ui.sender
        //});
        $(".multiselect").multiselect({
            sortable: false,
            searchable: true,
            selected: function (event, ui) {
                //  alert($(ui.option).val() + " has been selected"); 
            }
        });


    });

    $('#to_profile').click(function (t) {
        $("#dialog:ui-dialog").dialog("destroy");

        $('#profile_error_message').html('');

        // reset form
        $('#profile_firstname').val('');

        if ($('#profile_infix')[0] !== undefined) {
            $('#profile_infix').val('');
        }

        $('#profile_lastname').val('');
        $('#profile_country').val('');
        $('#profile_email').val('');
        $('#profile_birthdate_day').val('');
        $('#profile_birthdate_month').val('');
        $('#profile_birthdate_year').val('');

        // get user profile
        $.ajax({
            type: "POST",
            url: MyCalendar.FULLCAL_URL + "/index.php?action=get_profile",
            dataType: 'json',
            success: function (result) {
                if (result.success) {

                    $('#profile_firstname').val(result.profile.firstname);

                    if ($('#profile_infix')[0] !== undefined) {
                        $('#profile_infix')[0].value = result.profile.infix;
                    }

                    $('#profile_lastname').val(result.profile.lastname);
                    $('#profile_username').val(result.profile.username);
                    $('#profile_country').val(result.profile.country);
                    $('#profile_email').val(result.profile.email);

                    if (result.profile.birthdate_year !== '0000') {
                        $('#profile_birthdate_day').val(result.profile.birthdate_day);
                        $('#profile_birthdate_month').val(result.profile.birthdate_month);
                        $('#profile_birthdate_year').val(result.profile.birthdate_year);
                    }

                    $("#dialog-profile").dialog({
                        modal: true,
                        title: Lang.Menu.TitleProfile,
                        height: 470,
                        width: 520,
                        buttons: [{
                                html: Lang.Popup.saveButtonText,
                                click: function (a, b) {
                                    saveProfile();
                                }
                            }, {
                                html: Lang.Popup.closeButtonText,
                                click: function () {
                                    $(this).dialog("close");
                                }
                            }]
                    });

                    $('#profile_name_label_id').html(Lang.Popup.LabelName);
                    $('#profile_email_label_id').html(Lang.Popup.LabelEmail);
                    $('#profile_username_label_id').html(Lang.Popup.LabelUsername);
                    $('#profile_birthdate_label_id').html(Lang.Popup.LabelBirthdate);
                    $('#profile_country_label_id').html(Lang.Popup.LabelCountry);
                    $('#profile_new_password_label_id').html(Lang.Popup.LabelNewPassword);
                    $('#profile_new_password2_label_id').html(Lang.Popup.LabelNewPasswordAgain);

                    $("#profile_password").attr("placeholder", Lang.Popup.Placeholder.LeaveBlankForNoChange);
                    $("#profile_confirm").attr("placeholder", Lang.Popup.Placeholder.LeaveBlankForNoChange);

                } else {
                    if (result.notloggedin) {
                        alert(Lang.Alert.NotLoggedIn);
                        window.location = MyCalendar.FULLCAL_URL;
                    } else {
                        $('#adduser_error_message').html(result.error);
                    }
                }
            }
        });
    });

    var addUser = function (me) {
        var email = $('#adduser_email').val();
        var firstname = $('#adduser_firstname').val();
        var lastname = $('#adduser_lastname').val();
        var username = '';

        if ($('#adduser_infix') !== undefined) {
            var infix = $('#adduser_infix').val();
        }
        if ($('#adduser_username') !== undefined) {
            username = $('#adduser_username').val();
        }
        if ($('#adduser_copy_to_admin') !== undefined) {
            var copy_to_admin = $('#adduser_copy_to_admin').is(':checked');
        }

        if (!MyCalendar.isAdmin) {
            $('#adduser_error_message').html('You are not an admin');
        } else {

            if (lastname !== null && lastname !== '' && email !== null && email !== '') {
                var data = {
                    email: email,
                    firstname: firstname,
                    lastname: lastname,
                    username: username,
                    infix: infix,
                    copy_to_admin: copy_to_admin
                };

                $.ajax({
                    type: "POST",
                    url: MyCalendar.FULLCAL_URL + "/index.php?action=add_user",
                    data: data,
                    dataType: 'json',
                    success: function (result) {
                        if (result.password) {
                            alert(result.password);
                        }
                        if (result.success) {
                            me.dialog("close");
                            showMessage('User inserted and email send successfully', 'success');

                            //$('#adduser_error_message').html('User inserted and email send successfully');
                        } else {
                            if (result.notloggedin) {
                                alert(Lang.Alert.NotLoggedIn);
                                window.location = MyCalendar.FULLCAL_URL;
                            } else {
                                $('#adduser_error_message').html(result.error);
                            }
                        }
                    }
                });
            } else {
                $('#adduser_error_message').html('Name and email are required');
            }
        }
    }

    $('#dd').click(function (t) {
        $('#menu_to_profile').text(Lang.Menu.TitleProfile);
        $('#menu_add_user').text(MyCalendar.isSuperAdmin ? Lang.Menu.TitleAddAdmin : Lang.Menu.TitleAdduser);
        $('#menu_quick_add_user').text(MyCalendar.isSuperAdmin ? Lang.Menu.TitleQuickAddAdmin : Lang.Menu.TitleQuickAdduser);
        $('#menu_logout').text(Lang.Menu.TitleLogout);
        $('#menu_settings').text(Lang.Menu.TitleSettings);
        $('#menu_admin_area').text(Lang.Menu.TitleAdminArea);
    });

    $('#to_add_user').click(function (t) {
        $("#dialog:ui-dialog").dialog("destroy");

        $('#adduser_email')[0].value = '';
        $('#adduser_lastname')[0].value = '';

        if ($('#adduser_infix')[0] !== undefined) {
            $('#adduser_infix')[0].value = '';
        }

        if ($('#adduser_username')[0] !== undefined) {
            $('#adduser_username')[0].value = '';
        }

        if ($('#adduser_username')[0] !== undefined) {
            $('#adduser_username')[0].value = '';
        }

        $('#adduser_copy_to_admin').attr('checked', false);

        $('#adduser_firstname')[0].value = '';
        $('#adduser_error_message').html('');

        $("#dialog-adduser").dialog({
            modal: true,
            title: (MyCalendar.isSuperAdmin ? Lang.Menu.TitleAddAdmin : Lang.Menu.TitleAdduser),
            height: 310,
            width: 520,
            buttons: [{
                    html: Lang.Popup.addUserAndSendMailButtonText,
                    click: function (a, b) {
                        $('#adduser_error_message').html('');

                        var me = $(this);
                        addUser(me);
                    }
                }, {
                    html: Lang.Popup.closeButtonText,
                    click: function () {
                        $(this).dialog("close");
                    }
                }]
        });

        $('#adduser_name_label_id').html(Lang.Popup.LabelName);
        $('#adduser_email_label_id').html(Lang.Popup.LabelEmail);
        $('#adduser_username_label_id').html(Lang.Popup.LabelUsername);
        $('#adduser_password_text').html(Lang.Popup.AddUserPasswordText);

        $("#adduser_firstname").attr("placeholder", Lang.Popup.Placeholder.Firstname);
        $("#adduser_lastname").attr("placeholder", Lang.Popup.Placeholder.Lastname);

    });


    var saveSettings = function () {
        var language = $('#settings_language').val();
        var default_view = $('#settings_default_view').val();
        var timezone = $('#settings_timezone').val();
        var user_id = $('#settings_user_id').val();
        var other_language = $('#settings_other_language').val();

        var data = {
            language: language,
            other_language: other_language,
            default_view: default_view,
            timezone: timezone,
            user_id: user_id
        };

        $.ajax({
            type: "POST",
            url: MyCalendar.FULLCAL_URL + '/index.php?action=save_settings',
            data: data,
            dataType: 'json',
            success: function (result) {
                if (result.success) {
                    $("#dialog-to-settings").dialog("close");
                    showMessage('Settings saved successfully', 'success');

                } else {
                    if (result.notloggedin) {
                        alert(Lang.Alert.NotLoggedIn);
                        window.location = MyCalendar.FULLCAL_URL;
                    } else {
                        $('#settings_error_message').html(result.error);
                    }
                }
            }
        });

    };

    $('#to_settings').click(function (t) {
        $("#dialog:ui-dialog").dialog("destroy");

        if ($('#settings_language')[0] !== undefined) {
            $('#settings_language')[0].value = '';
        }
        if ($('#settings_default_view')[0] !== undefined) {
            $('#settings_default_view')[0].value = '';
        }
        if ($('#settings_timezone')[0] !== undefined) {
            $('#settings_timezone')[0].value = '';
        }

        $('#settings_error_message').html('');

        // get user profile
        $.ajax({
            type: "POST",
            url: MyCalendar.FULLCAL_URL + "/index.php?action=get_settings",
            dataType: 'json',
            success: function (result) {
                if (result.success) {

                    $('#settings_language').val(result.settings.language);
                    $('#settings_default_view').val(result.settings.default_view);
                    $('#settings_timezone').val(result.settings.timezone);
                    $('#settings_other_language').val(result.settings.other_language);

                    $("#dialog-to-settings").dialog({
                        modal: true,
                        title: 'Settings',
                        height: 350,
                        width: 520,
                        buttons: [{
                                html: Lang.Popup.saveButtonText,
                                click: function (a, b) {
                                    saveSettings();
                                }
                            }, {
                                html: Lang.Popup.closeButtonText,
                                click: function () {
                                    $(this).dialog("close");
                                }
                            }]
                    });

                    $('#admin_settings_default_view_label').html(Lang.Popup.LabelName);
                    $('#admin_settings_language_label').html(Lang.Popup.LabelEmail);


                } else {
                    if (result.notloggedin) {
                        alert(Lang.Alert.NotLoggedIn);
                        window.location = MyCalendar.FULLCAL_URL;
                    } else {
                        $('#settings_error_message').html(result.error);
                    }
                }
            }
        });
        $('#admin_settings_default_view_label').html(Lang.Popup.LabelName);
        $('#admin_settings_language_label').html(Lang.Popup.LabelEmail);


    });

    $("#hourcalc_datepicker_startdate").datepicker({
        dateFormat: MyCalendar.datePickerDateFormat || 'dd/mm/yy',
        firstDay: MyCalendar.FCfirstDay,
        onSelect: function (dateText, inst) {
            var dp_enddate = $("#hourcalc_datepicker_enddate").datepicker('getDate');

            if (dp_enddate !== null) {
                if (MyCalendar.datePickerDateFormat === null || MyCalendar.datePickerDateFormat == 'dd/mm/yy') {
                    var arr_startdate = dateText.split('/');
                    var dp_startdate = new Date(arr_startdate[1] + '/' + arr_startdate[0] + '/' + arr_startdate[2]);
                } else if (MyCalendar.datePickerDateFormat == 'mm/dd/yy') {
                    var dp_startdate = new Date(dateText);
                }

                if (dp_startdate.getTime() > dp_enddate.getTime()) {
                    $('#error_message').html(Lang.Alert.DatesNotCorrect);
                    $("#hourcalc_datepicker_startdate").datepicker('setDate', dp_startdate);

                }
            }
            return true;
        }
    });

    $("#hourcalc_datepicker_enddate").datepicker({
        dateFormat: MyCalendar.datePickerDateFormat || 'dd/mm/yy',
        firstDay: MyCalendar.FCfirstDay,
        onSelect: function (dateText, inst) {
            var dp_startdate = $("#hourcalc_datepicker_startdate").datepicker('getDate');

            if (dp_startdate !== null) {
                if (MyCalendar.datePickerDateFormat === null || MyCalendar.datePickerDateFormat == 'dd/mm/yy') {
                    var arr_enddate = dateText.split('/');
                    var dp_enddate = new Date(arr_enddate[1] + '/' + arr_enddate[0] + '/' + arr_enddate[2]);
                } else if (MyCalendar.datePickerDateFormat == 'mm/dd/yy') {
                    var dp_enddate = new Date(dateText);
                }

                if (dp_startdate.getTime() > dp_enddate.getTime()) {
                    $('#error_message').html(Lang.Alert.DatesNotCorrect);
                    $("#hourcalc_datepicker_enddate").datepicker('setDate', dp_startdate);

                }
            }
            return true;
        }
    });

    $("#active_period_datepicker_startdate").datepicker({
        dateFormat: MyCalendar.datePickerDateFormat || 'dd/mm/yy',
        firstDay: MyCalendar.FCfirstDay,
        onSelect: function (dateText, inst) {

            var dp_enddate = $("#active_period_datepicker_enddate").datepicker('getDate');

            if (dp_enddate !== null) {
                if (MyCalendar.datePickerDateFormat === null || MyCalendar.datePickerDateFormat == 'dd/mm/yy') {
                    var arr_startdate = dateText.split('/');
                    var dp_startdate = new Date(arr_startdate[1] + '/' + arr_startdate[0] + '/' + arr_startdate[2]);
                } else if (MyCalendar.datePickerDateFormat == 'mm/dd/yy') {
                    var dp_startdate = new Date(dateText);
                }

                if (dp_startdate.getTime() > dp_enddate.getTime()) {
                    $('#error_message').html(Lang.Alert.DatesNotCorrect);
                    $("#active_period_datepicker_startdate").datepicker('setDate', dp_startdate);

                }
            }


            return true;
        }
    });

    $("#active_period_datepicker_enddate").datepicker({
        dateFormat: MyCalendar.datePickerDateFormat || 'dd/mm/yy',
        firstDay: MyCalendar.FCfirstDay,
        onSelect: function (dateText, inst) {
            var dp_startdate = $("#active_period_datepicker_startdate").datepicker('getDate');

            if (dp_startdate !== null) {
                if (MyCalendar.datePickerDateFormat === null || MyCalendar.datePickerDateFormat == 'dd/mm/yy') {
                    var arr_enddate = dateText.split('/');
                    var dp_enddate = new Date(arr_enddate[1] + '/' + arr_enddate[0] + '/' + arr_enddate[2]);
                } else if (MyCalendar.datePickerDateFormat == 'mm/dd/yy') {
                    var dp_enddate = new Date(dateText);
                }

                if (dp_startdate.getTime() > dp_enddate.getTime()) {
                    $('#error_message').html(Lang.Alert.DatesNotCorrect);
                    $("#active_period_datepicker_enddate").datepicker('setDate', dp_startdate);

                }
            }
            return true;
        }
    });

    $("#alterable_period_datepicker_startdate").datepicker({
        dateFormat: MyCalendar.datePickerDateFormat || 'dd/mm/yy',
        firstDay: MyCalendar.FCfirstDay,
        onSelect: function (dateText, inst) {

            var dp_enddate = $("#alterable_period_datepicker_enddate").datepicker('getDate');

            if (dp_enddate !== null) {
                if (MyCalendar.datePickerDateFormat === null || MyCalendar.datePickerDateFormat == 'dd/mm/yy') {
                    var arr_startdate = dateText.split('/');
                    var dp_startdate = new Date(arr_startdate[1] + '/' + arr_startdate[0] + '/' + arr_startdate[2]);
                } else if (MyCalendar.datePickerDateFormat == 'mm/dd/yy') {
                    var dp_startdate = new Date(dateText);
                }

                if (dp_startdate.getTime() > dp_enddate.getTime()) {
                    $('#error_message').html(Lang.Alert.DatesNotCorrect);
                    $("#alterable_period_datepicker_startdate").datepicker('setDate', dp_startdate);

                }
            }


            return true;
        }
    });

    $("#alterable_period_datepicker_enddate").datepicker({
        dateFormat: MyCalendar.datePickerDateFormat || 'dd/mm/yy',
        firstDay: MyCalendar.FCfirstDay,
        onSelect: function (dateText, inst) {
            var dp_startdate = $("#alterable_period_datepicker_startdate").datepicker('getDate');

            if (dp_startdate !== null) {
                if (MyCalendar.datePickerDateFormat === null || MyCalendar.datePickerDateFormat == 'dd/mm/yy') {
                    var arr_enddate = dateText.split('/');
                    var dp_enddate = new Date(arr_enddate[1] + '/' + arr_enddate[0] + '/' + arr_enddate[2]);
                } else if (MyCalendar.datePickerDateFormat == 'mm/dd/yy') {
                    var dp_enddate = new Date(dateText);
                }

                if (dp_startdate.getTime() > dp_enddate.getTime()) {
                    $('#error_message').html(Lang.Alert.DatesNotCorrect);
                    $("#alterable_period_datepicker_enddate").datepicker('setDate', dp_startdate);

                }
            }
            return true;
        }
    });

    $('#interval_div').click(function () {
        var me = $(this);
        //if(me.val() == 'D') {
        $('#info_txt').html('');
        //}
        $('#interval_week_choice_div').hide();
        $('#interval_month_choice_div').hide();
        $('#interval_year_choice_div').hide();
        $('#interval_day_choice_div').hide();

        if (me.val() == 'D') {
            $('#interval_day_choice_div').show();
            $('#info_txt').html('');

        } else if (me.val() == 'W') {
            $('#interval_week_choice_div').show();
            $('#info_txt').html('');

            // check the weekday checkbox from the chosen startdate
            var dp_startdate = $("#datepicker_startdate").datepicker('getDate');
            var dp_enddate = $("#datepicker_enddate").datepicker('getDate');

            var weekdaynumber = dp_startdate.getDay();

            // initieel: vink de dag aan die valt op de startdatum
            $("#interval_week_choice_div input[name='day_" + weekdaynumber + "']").attr('checked', true);

            var rep_week_text = '';
            var weekdays = '';
            var counter = 0;
            var rep_week_text = Lang.Popup.WeeklyOn + ' ';

            $('#interval_week_choice_div input:checkbox').each(function (index, item) {
                if ($(item).is(':checked')) {
                    if (counter > 0) {
                        rep_week_text += ', ';
                    }
                    var value = $(item).val();

                    rep_week_text += Lang.Fullcalendar.dayNames[value == 7 ? 0 : value];
                    counter++;
                }
            });
            $('#info_txt').html(rep_week_text);

        } else if (me.val() == 'M') {
            $("#monthly_dom").attr('checked', true);
            $('#interval_month_choice_div').show();
            $('#info_txt').html(Lang.Popup.MonthlyOnDay + ' ' + $("#datepicker_startdate").datepicker('getDate').getDate());

        } else if (me.val() == 'Y') {
            var yearly_dom = $("#datepicker_startdate").datepicker('getDate').getDate();
            var yearly_month = Lang.Fullcalendar.monthNames[$("#datepicker_startdate").datepicker('getDate').getMonth()];

            var ts_endyear_recurring_date = Date.parse((parseInt($("#datepicker_startdate").datepicker('getDate').getMonth()) + 1) + '/' + yearly_dom + '/' + $("#datepicker_enddate").datepicker('getDate').getFullYear());
            var ts_enddate = $("#datepicker_enddate").datepicker('getDate').getTime();

            var until = '';

            if (ts_endyear_recurring_date <= ts_enddate) {
                until = $("#datepicker_enddate").datepicker('getDate').getFullYear();
            } else {
                until = $("#datepicker_enddate").datepicker('getDate').getFullYear() - 1;
            }

            $('#yearly_dom').val(yearly_dom);
            $('#yearly_month').val($("#datepicker_startdate").datepicker('getDate').getMonth());
            $('#interval_year_choice_div').show();
            $('#info_txt').html(Lang.Popup.YearlyOn + ' ' + yearly_dom + ' ' + yearly_month + ' ' + Lang.Popup.Until + ' ' + until);
        }
    });

    $('#monthly_dom').click(function () {
        var me = $(this);

        $('#info_txt').html(Lang.Popup.MonthlyOnDay + ' ' + $("#datepicker_startdate").datepicker('getDate').getDate());

    });

    $('#monthly_dow').click(function () {
        var me = $(this);

        var arr_date = MyCalendar.datePickerDateFormat.split('/');

        $('#info_txt').html(Lang.Popup.MonthlyOn + ' ' + Lang.Fullcalendar.dayNames[$("#datepicker_startdate").datepicker('getDate').getDay()] + ', ' + Lang.Popup.Starting + ' ' + (arr_date[0] == 'dd' ? $("#datepicker_startdate").datepicker('getDate').format('dd/mm') : $("#datepicker_startdate").datepicker('getDate').format('mm/dd')));

    });

    $('#yearly_month').click(function (t) {
        var yearly_dom = $('#yearly_dom').val();
        var yearly_month = $(this).val();

        var ts_endyear_recurring_date = Date.parse((parseInt(yearly_month) + 1) + '/' + yearly_dom + '/' + $("#datepicker_enddate").datepicker('getDate').getFullYear());
        var ts_enddate = $("#datepicker_enddate").datepicker('getDate').getTime();

        var until = '';

        if (ts_endyear_recurring_date <= ts_enddate) {
            until = $("#datepicker_enddate").datepicker('getDate').getFullYear();
        } else {
            until = $("#datepicker_enddate").datepicker('getDate').getFullYear() - 1;
        }

        $('#interval_year_choice_div').show();
        $('#info_txt').html(Lang.Popup.YearlyOn + ' ' + yearly_dom + ' ' + Lang.Fullcalendar.monthNames[yearly_month] + ' ' + Lang.Popup.Until + ' ' + until);

    });

    $('#yearly_dom').change(function (t) {
        var yearly_dom = $(this).val();
        var yearly_month = $('#yearly_month').val();

        var ts_endyear_recurring_date = Date.parse((parseInt(yearly_month) + 1) + '/' + yearly_dom + '/' + $("#datepicker_enddate").datepicker('getDate').getFullYear());

        var ts_enddate = $("#datepicker_enddate").datepicker('getDate').getTime();

        var until = '';

        if (ts_endyear_recurring_date <= ts_enddate) {
            until = $("#datepicker_enddate").datepicker('getDate').getFullYear();
        } else {
            until = $("#datepicker_enddate").datepicker('getDate').getFullYear() - 1;
        }

        $('#interval_year_choice_div').show();
        $('#info_txt').html(Lang.Popup.YearlyOn + ' ' + yearly_dom + ' ' + Lang.Fullcalendar.monthNames[yearly_month] + ' ' + Lang.Popup.Until + ' ' + until);

    });



    $('#datepicker_enddate').change(function () {
        if ($('#interval_div').val() == 'Y') {
            var ts_endyear_recurring_date = Date.parse((parseInt($("#yearly_month").val()) + 1) + '/' + $("#yearly_dom").val() + '/' + $("#datepicker_enddate").datepicker('getDate').getFullYear());

            var until = '';

            if (ts_endyear_recurring_date <= $("#datepicker_enddate").datepicker('getDate').getTime()) {
                until = $("#datepicker_enddate").datepicker('getDate').getFullYear();
            } else {
                until = $("#datepicker_enddate").datepicker('getDate').getFullYear() - 1;
            }

            var yearly_month = Lang.Fullcalendar.monthNames[$("#yearly_month").val()];

            $('#info_txt').html(Lang.Popup.YearlyOn + ' ' + $("#yearly_dom").val() + ' ' + yearly_month + ' ' + Lang.Popup.Until + ' ' + until);
        }


    });




});