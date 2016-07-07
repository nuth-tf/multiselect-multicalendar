var Lang = {};

Lang.Fullcalendar = {};
Lang.Fullcalendar.monthNames = ['Januar', 'Februar', 'Mars', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Desember'];
Lang.Fullcalendar.monthNamesShort = ['Jan', 'Feb', 'Mar', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Des'];
Lang.Fullcalendar.dayNames = ['Søndag', 'Mandag', 'Tirsdag', 'Onsdag', 'Torsdag', 'Fredag', 'Lørdag'];
Lang.Fullcalendar.dayNamesShort = ['Søn', 'Man', 'Tir', 'Ons', 'Tor', 'Fre', 'Lør'];

Lang.Fullcalendar.buttonText = {};
Lang.Fullcalendar.buttonText.today = 'I dag';
Lang.Fullcalendar.buttonText.month = 'Måned';
Lang.Fullcalendar.buttonText.week = 'Uke';
Lang.Fullcalendar.buttonText.day = 'dag';
Lang.Fullcalendar.buttonText.agendaList = 'liste';

// DIALOG
Lang.Popup = {};
Lang.Popup.TitleAdd = 'Legg til en hendelse';
Lang.Popup.TitleView = 'Se på en hendelse';
Lang.Popup.TitleEdit = 'Rediger en hendelse';

// DIALOG BUTTONS
Lang.Popup.closeButtonText = 'Lukk';
Lang.Popup.saveButtonText = 'Lagre';
Lang.Popup.editButtonText = 'Rediger';
Lang.Popup.cancelButtonText = 'Cancel';
Lang.Popup.deleteButtonText = 'Slett';
Lang.Popup.saveAndRefreshButtonText = 'Lagre & oppdater';
Lang.Popup.updateButtonText = 'Oppdater';
Lang.Popup.addUserAndSendMailButtonText = 'Legg til en bruker og send e-post';
Lang.Popup.emailText = 'E-post til admin';
Lang.Popup.copyText = 'Copy';
Lang.Popup.copyEventText = 'Copy event';
Lang.Popup.copyToText = 'Copy to';
Lang.Popup.disconnectText = 'Disconnect';

// FORM LABELS
Lang.Popup.allDayLabel = 'Hele dager';
Lang.Popup.MonthLabel = 'Dato';
Lang.Popup.TimeLabel = 'Tid';
Lang.Popup.StartdateLabel = 'Start tidspunkt';
Lang.Popup.EnddateLabel = 'Slutt dato';
Lang.Popup.SimpleStartTimeLabel = 'Fra';
Lang.Popup.SimpleEndTimeLabel = 'Til';
Lang.Popup.EventTitle = 'Navn';
Lang.Popup.EventLocation = 'Plassering';
Lang.Popup.EventDescription = 'Beskrivelse';
Lang.Popup.EventPhone = 'Telefon nr.';
Lang.Popup.EventUrl = 'Url';
Lang.Popup.EventInterval = 'Interval';
Lang.Popup.EventRecurrence = 'Gjentagelse';
Lang.Popup.EditRecurringEvent = 'Edit recurring event';

Lang.Popup.ProfileEventColor = 'Farge på hendelsen';
Lang.Popup.EventColor = 'Farger';
Lang.Popup.EventColorUseForAllEvents = 'Brukes på alle mine hendelser';
Lang.Popup.DayOfMonth = 'Dag og måned';
Lang.Popup.DayOfWeek = 'Dag og uke';
Lang.Popup.MonthlyOnDay = 'Monthly on day';
Lang.Popup.MonthlyOn = 'Monthly on';
Lang.Popup.Starting = 'starting';
Lang.Popup.Daily = 'daily';
Lang.Popup.DailyOn = 'Daily on';
Lang.Popup.Every = 'hver';
Lang.Popup.Weeks = 'uke';
Lang.Popup.On = 'på';
Lang.Popup.WeeklyOn = 'Weekly on';
Lang.Popup.YearlyOn = 'Yearly on';
Lang.Popup.Until = 'Until';

Lang.Popup.LabelTabMain = 'Main';
Lang.Popup.LabelTabFiles = 'Files';
Lang.Popup.LabelActive = 'Active';
Lang.Popup.LabelName = 'Navn/tittel';
Lang.Popup.LabelEmail = 'E-post';
Lang.Popup.LabelUsername = 'Brukernavn';
Lang.Popup.LabelLogin = 'Login';
Lang.Popup.LabelBirthdate = 'Birthdate';
Lang.Popup.LabelCountry = 'Land';
Lang.Popup.LabelLanguage = 'Språk';
Lang.Popup.DefaultView = 'standardvisning';
Lang.Popup.LabelPassword = 'Passord';
Lang.Popup.LabelPasswordAgain = 'Gjenta passord';
Lang.Popup.LabelNewPassword = 'Nytt passord';
Lang.Popup.LabelNewPasswordAgain = 'Gjenta nytt passord';
Lang.Popup.AddUserPasswordText = 'Et passord vil bli generert og inkluderes i e-posten.';
Lang.Popup.AddUserActivationMailText = 'Brukeren kan aktivere aktiverings-linken i tilsendt e-post.';
Lang.Popup.LabelCopyToAdmin = 'Kopier til administrator';
Lang.Popup.LabelCalendar = 'Calendar';
Lang.Popup.AssignedBy = 'Tildelt av';
Lang.Popup.Assigned = 'Tildelt';

// FORM PLACEHOLDERS
Lang.Popup.Placeholder = {};
Lang.Popup.Placeholder.LeaveBlankForNoChange = 'Ingen endring, la det stå tomt';
Lang.Popup.Placeholder.Firstname = 'Navn';
Lang.Popup.Placeholder.Lastname = 'Etternavn';

// TIMEPICKER
Lang.Popup.TimepickerHourtext = 'Timer';
Lang.Popup.TimepickerMinutetext = 'Minutt';
Lang.Popup.TimepickercloseButtonText = 'OK';
Lang.Popup.TimepickernowButtonText = 'Nå';

Lang.Popup.MycalendarTitle = 'Min kalender';

// MENU
Lang.Menu = {};
Lang.Menu.TitleProfile = 'Profil';
Lang.Menu.TitleAdduser = 'Legg til bruker';
Lang.Menu.TitleQuickAdduser = 'Quickly add user';
Lang.Menu.TitleAddAdmin = 'Legg til administrator';
Lang.Menu.TitleQuickAddAdmin = 'Quickly add admin';
Lang.Menu.TitleLogout = 'Logg ut';
Lang.Menu.TitleUsers = 'Bruker';
Lang.Menu.TitleAdmins = 'Admins';
Lang.Menu.TitleCalendars = 'Kalendere';
Lang.Menu.TitleHourCalculation = 'Time beregning';
Lang.Menu.TitleSettings = 'Innstillinger';
Lang.Menu.TitleAdminArea = 'Admin område';
Lang.Menu.TitleGroups = 'Groups';
Lang.Menu.TitleMyGroups = 'My groups';
Lang.Menu.TitleMyCalendars = 'My calendars';
Lang.Menu.TitleAvailability = 'Availability';
Lang.Menu.TitleAddAvailability = 'Add availability';
Lang.Menu.TitleImportUsers = 'Import users';

Lang.Button = {};
Lang.Button.addCalendar = 'Legg til en kalender';
Lang.Button.forgottenPassword = 'Forgotten password';
Lang.Button.register = 'Register';
Lang.Button.AddDDItem = 'Add a DD-item';
Lang.Button.AddLocation = 'Add a location';
Lang.Button.SaveChanges = 'Save changes';
Lang.Button.AddGroup = 'legge til gruppen';
Lang.Button.Refresh = 'Forfriske';
Lang.Button.AddAvailability = 'Add availability';

// ALERTS
Lang.Alert = {};
Lang.Alert.NotAllowedToAdd = 'Du kan ikke legge til en hendelse før du er legget inn.';
Lang.Alert.NotAllowedToEdit = 'Du kan ikke endre denne hendelsen.';
Lang.Alert.NotAllowedToEditGoogleEvent = 'Du kan ikke endre denne hendelsen, vil du bli omdirigert til Google';
Lang.Alert.NotAllowedToAddOnThisDate = 'Denne datoen er utelukket';
Lang.Alert.CalendarOnlyViewable = 'Du kan bare vise denne kalenderen';

Lang.Alert.DatesNotCorrect = 'Feil i datoene.';
Lang.Alert.TimesNotCorrect = 'Feil i tidsplaner.';
Lang.Alert.EventTitleRequired = 'Tittel er påkrevet.';
Lang.Alert.FillInTheRequiredFields = 'Fill in the required fields';
Lang.Alert.ErrorSaving = 'Feil under lagring. Prøv igjen senere';
Lang.Alert.NotLoggedIn = 'Påloggingen er utløpt. Du vil bli omdirigert til påloggingssiden.';
Lang.Alert.Error = 'Error';
Lang.Alert.ChooseCalendarFirst = 'Velg en kalender først';
Lang.Alert.DoNotForgetToSave = 'Ikke glem å lagre hendelsen!';
Lang.Alert.SettingsSavedSuccess = 'Innstillinger lagret';

Lang.Alert.FileTooBig = 'Filen du prøver å laste opp er for stor';
Lang.Alert.PartiallyUploaded = 'Filen du prøver Opplastingen ble bare delvis opplastet.';
Lang.Alert.NoFileSelected = 'Du må velge en fil for opplasting.';
Lang.Alert.ProblemWithUpload = 'Det var et problem med opplasting.';
Lang.Alert.LogInToUpload = 'Du må være innlogget for å laste opp filer';
Lang.Alert.FileNotAllowed = 'Denne filtypen er ikke tillatt';
Lang.Alert.EventAlreadyOpened = 'Denne hendelsen er åpnet av noen andre, prøv igjen senere.';

Lang.Database = {};
Lang.Database.ConnectFailed = 'Databasetilkobling mislyktes';

// PROMPTS
Lang.Prompt = {};
Lang.Prompt.Disconnect = {};
Lang.Prompt.Disconnect.title = 'Koble fra mønster';
Lang.Prompt.Disconnect.text = 'Er du sikker på at du vil koble dette elementet fra mønsteret?';

Lang.Prompt.Update = {};
Lang.Prompt.Update.repairText = 'Reparasjon av gjentagende mønster?';
Lang.Prompt.Update.updateOneOrAllTitle = 'Oppdater element (er)';
Lang.Prompt.Update.chooseOneOrAllText = 'Ønsker du å redigere dette elementet eller hele mønsteret?';
Lang.Prompt.Update.thisItemBtn = 'Dette elementet';
Lang.Prompt.Update.allItemsBtn = 'Hele mønster';

Lang.Prompt.Delete = {};
Lang.Prompt.Delete.RemoveBtn = 'Fjern';
Lang.Prompt.Delete.CancelBtn = 'Avbryt';
Lang.Prompt.Delete.ConfirmTitle = 'Fjern element';
Lang.Prompt.Delete.ConfirmText = 'Er du sikker på at du vil fjerne dette elementet?';
Lang.Prompt.Delete.chooseOneOrAllTitle = 'Fjern element(er)';
Lang.Prompt.Delete.chooseOneOrAllText = 'Ønsker du å fjerne bare dette elementet eller hele mønsteret?';
Lang.Prompt.Delete.thisItemBtn = 'dette element';
Lang.Prompt.Delete.allItemsBtn = 'Alt sammen';

Lang.Settings = {};
Lang.Settings.Legend = 'Settings';
Lang.Settings.Infotext = '.These settings will only be applied when someone is logged in, otherwise the settings from config.php will be used.';
Lang.Settings.DefaultView = 'Default view';
Lang.Settings.LabelWeekViewType = 'Visningstype, uke';
Lang.Settings.LabelDayViewType = 'Visningstype, dag';
Lang.Settings.LabelLanguage = 'Språk';
Lang.Settings.LabelOtherLanguage = 'Andre Språk';
Lang.Settings.LabelOtherLanguageInfo = 'To store tegn ';
Lang.Settings.LabelShowAmPm = 'Vis AM/PM';
Lang.Settings.LabelShowWeeknumbers = 'Vis ukenummer';
Lang.Settings.LabelShowNotAllowedMessages = 'Vis "ikke tillatt" -meldinger';
Lang.Settings.LabelMouseoverPopup = 'Mouseover popup';
Lang.Settings.LabelTruncateTitle = 'Avkortet tittel';
Lang.Settings.LabelTitleLength = 'Title length';
Lang.Settings.LabelAmountOfCharacters = 'Antall tegn';
Lang.Settings.LabelEditDialog = 'Rediger dialogboksen';
Lang.Settings.LabelColorPickerType = 'Colorpicker type';
Lang.Settings.LabelTimePickerType = 'Timepicker type';
Lang.Settings.LabelMouseoverPopup = 'Show mouseoverpopup';
Lang.Settings.LabelTwoCapitals = 'To store tegn';
Lang.Settings.LabelShowDescription = 'Vis rubrikk for beskrivelse';
Lang.Settings.LabelShowLocation = 'Vis rubrikk for tilholdssted';
Lang.Settings.LabelShowPhone = 'Vis rubrikk for telefon';
Lang.Settings.LabelShowDeleteConfirmDialog = 'Vis dialogboksen Bekreft når du sletter et element';
Lang.Settings.LabelHourcalculation = 'Time beregning';
Lang.Settings.LabelWorkdayHours = 'Workday hours';
Lang.Settings.LabelDefaultPeriod = 'Default period';
Lang.Settings.LabelWorkdayHoursInfo = 'Amount of hours in a workday';
Lang.Settings.LabelDefaultPeriodInfo = 'Initial period in months';
Lang.Settings.LabelRegistration = 'Registration';
Lang.Settings.LabelRegistrationInfo = 'can be set in config.php';
Lang.Settings.LabelSendActivationMail = 'Send activationmail (Når du registrerer deg på nettstedet, er innstillingen fra config.php brukt)';

Lang.Hourcalculation = {};
Lang.Hourcalculation.legend = 'Time beregning';
Lang.Hourcalculation.legendOfUser = 'Time beregning av ';

Lang.ListView = {};
Lang.ListView.descriptionLabel = 'Info: ';
Lang.ListView.locationLabel = 'Plassering: ';
Lang.ListView.phoneLabel = 'Phone: ';
Lang.ListView.urlLabel = 'Url: ';

Lang.Label = {};
Lang.Label.Yes = 'Ja';
Lang.Label.No = "Nei";
Lang.Label.Color = 'Color';

Lang.Calendar = {};
Lang.Calendar.LabelEverybody = 'Everybody';
Lang.Calendar.LabelCanView = 'can see';
Lang.Calendar.LabelGroup = 'Group ';
Lang.Calendar.LabelActivePeriod = "Aktiv periode";
Lang.Calendar.LabelAlterablePeriod = 'alter periode';
Lang.Calendar.LabelActive = "Aktiv";
Lang.Calendar.LabelInSpecificPeriod = 'In bestemt periode';
Lang.Calendar.LabelName = 'Navn';
Lang.Calendar.LabelDDItems = 'Drag & Drop elementer';
Lang.Calendar.LabelTitle = 'Tittel';
Lang.Calendar.LabelInfo = 'Info';
Lang.Calendar.LabelCanSeeDDItems = 'Kan se DD-elementer';
Lang.Calendar.LabelShareType = 'Del med';
Lang.Calendar.LabelOthers = 'Annet';
Lang.Calendar.LabelOthersCan = 'Annet kan';
Lang.Calendar.LabelCanAdd = 'legge';
Lang.Calendar.LabelCanEdit = 'kan redigere';
Lang.Calendar.LabelCanDelete = 'kan slette';
Lang.Calendar.LabelCanChangeColor = 'kan endre farge';
Lang.Calendar.LabelDefaultCalendar = 'Kalender er åpen på start';
Lang.Calendar.LabelEventLocation = 'plassering felttype';
Lang.Calendar.LabelUseColorForAllEvents = 'Bruk aktuell farge for alle hendelsene i denne kalenderen';
Lang.Calendar.LabelPredefinedLocations = 'forhåndsdefinerte steder';
Lang.Calendar.LabelNotifications = 'Påminnelser (bare når en bruker er logget inn)';
Lang.Calendar.LabelManually = 'manuelt';
Lang.Calendar.LabelManuallyInfo = 'Brukere kan sende en hendelse til admin / arbeidsgiver';
Lang.Calendar.LabelAutomatic = 'Automatisk';
Lang.Calendar.LabelAutomaticInfo = 'En e-event endringer blir automatisk sende til admin / arbeidsgiver';
Lang.Calendar.LabelCalendarAdminEmail = 'Admin e-postadressen';
Lang.Calendar.LabelWhenAdminEmailEmptyInfo = 'Når tom: MAIL_EVENT_MAILADDRESS fra config.php brukes';

Lang.Calendar.LabelDays = 'Dager';
Lang.Calendar.LabelHours = 'Timer';
Lang.Calendar.LabelDate = 'Dato';
Lang.Calendar.LabelTime = 'Tid';
Lang.Calendar.LabelCalendar = 'Kalender';
Lang.Calendar.LabelUsername = 'Brukernavn';
Lang.Calendar.LabelEmail = 'E-post';
Lang.Calendar.LabelRegistrationDate = 'Registreringsdato';
Lang.Calendar.LabelActive = "Aktiv";
Lang.Calendar.LabelCountUsers = 'telle brukere';
Lang.Calendar.LabelOwner = 'eier';
Lang.Calendar.LabelOrigin = 'Opprinnelse';
Lang.Calendar.LabelCanSeeDDItems = 'Kan se DD-elementer';
Lang.Calendar.LabelType = 'Type';
Lang.Calendar.LabelAdd = "Legg til";
Lang.Calendar.LabelEdit = "Rediger";
Lang.Calendar.LabelDelete = 'Slett';
Lang.Calendar.LabelChangeColor = 'Endre farge';
Lang.Calendar.LabelInitialShow = 'Initial showet';
Lang.Calendar.LabelDDItemsCalForm = 'DD-elementer (Tømme tittelen feltet for å fjerne DD - elementet)';
Lang.Calendar.LabelDDItems = 'DD-elementer';
Lang.Calendar.LabelUsergroupDDItems = 'Brukergruppe DD-elementer';
Lang.Calendar.LabelAssignToUsers = 'Tilordne brukere';
Lang.Calendar.LabelView = 'Offentlig visning';
Lang.Calendar.LabelAssigning = 'Tildele';
Lang.Calendar.LabelAddCalendar = 'Add calendar';

Lang.Calendar.LabelTableLook = 'Tabell utseende';
Lang.Calendar.LabelShowTimeColumns = 'Vis tids kolonner';
Lang.Calendar.LabelShowDateOnEveryLine = 'Vis dato på hver linje';
Lang.Calendar.LabelShowLogo = 'Vis din logo';
Lang.Calendar.LabelFontWeightBold = 'Font vekt fet';
Lang.Calendar.LabelRowColorsGrayWhite = 'Row farger grå / hvit';
Lang.Calendar.LabelPdfExport = 'PDF-eksport';
Lang.Calendar.LabelPaginationTranslation = 'paginering oversettelse';
Lang.Calendar.LabelOf = 'av';
Lang.Calendar.LabelPage = 'side';
Lang.Calendar.LabelColumnNames = 'Tabell kolonnenavn';
Lang.Calendar.LabelDateColumn = 'Dato kolonne';
Lang.Calendar.LabelStartTimeColumn = 'Starttid kolonne';
Lang.Calendar.LabelEndTimeColumn = 'Sluttid kolonne';
Lang.Calendar.LabelEventTitleColumn = 'Tittel kolonne';
Lang.Calendar.LabelShowCustomDropdownValues = 'Show the custom dropdown values';

Lang.Calendar.LabelSorting = "samme som kalender sekvens";
Lang.Calendar.LabelShowTeamMember = 'Showet team- medlem felt';

Lang.Calendar.ButtonCustomList = 'definert liste';


