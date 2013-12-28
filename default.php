<?php if (!defined('APPLICATION')) exit();

// Define the plugin:
$PluginInfo['MobileDefaultRoute'] = array(
   'Name' => 'Mobile Default Route',
   'Description' => 'In settings you can set what you want for your homepage, effectively the default controller/view, this plugin allows a different route for your mobile site.',
   'Version' => '0.2.1b',
   'Author' => "Paul Thomas",
   'SettingsUrl' => '/dashboard/settings/mobiledefaultroute',
   'RequiredApplications' => array('Vanilla' => '>=2.0.18'),
   'AuthorEmail' => 'dt01pqt_pt@yahoo.com',
   'AuthorUrl' => 'http://vanillaforums.org/profile/x00'
);

class MobileDefaultRoute extends Gdn_Plugin {

  public function Base_GetAppSettingsMenuItems_Handler($Sender) {
    $Menu = $Sender->EventArguments['SideMenu'];
    $Menu->AddLink('Appearance', T('Mobile Default Route'), 'settings/mobiledefaultroute', 'Garden.Settings.Manage');
  }

  public function SettingsController_MobileDefaultRoute_Create($Sender){
    $Sender->Permission('Garden.Settings.Manage');
    $Config = new ConfigurationModule($Sender);
    $Config->Initialize(array(
      'Plugins.MobileDefaultRoute.Destination' => array(
        'Type' => 'string',
        'Control' => 'TextBox',
        'Default' => null, 
        'Description' => 'The default route for mobile site (blank to disable)'
        )
      )
    );

    $Sender->AddSideMenu('settings/mobiledefaultroute');
    $Sender->SetData('Title', T('Mobile Default Route'));
    $Config->RenderAll();
  }

  public function Base_BeforeDispatch_Handler($Sender,&$Args){
    if(!(IsMobile() && C('Plugins.MobileDefaultRoute.Destination'))) return;
    
    $Routes=&Gdn::Router()->Routes;
    if(GetValue('DefaultController',$Routes))
      $Routes['DefaultController']['Destination']=C('Plugins.MobileDefaultRoute.Destination');
  }

}
