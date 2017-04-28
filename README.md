# dahua-camera-ptz

> This is a simple PHP implementation of Dahua IP Cam HTTP API.

> Impelements both Basic and Digest auth, this script should be able to handle both new and old firmware, and 3rd Party branded Dahua cameras.

## Config

> The script requires these configs:

* user: Username for ipCam
* pass: password for ipCam
* cam_list:  a list of the ip cameras.
* basic_auth_list:  a list of ip cameras that requires Basic Auth

## Arguments
> This script takes the following querystrings:
* c : camera number specified in cam_list
* a : API_CALL you wish to execute. See Dahua HTTP API documentation for example
* p : ptz preset. If this is specified, the script ignores the API Call you set in 'a', and will execute PTZ - GotoPreset.
* d : debug flag. set to 1 to view the full header info.

## Example
> After the configuration, you can save the script on a web host with PHP enabled, and call the script like this:
> >  http://HOSTNAME/SCRIPTNAME.php?c=CAM_NUMBER&a=API_CALL_NAME

> For instance, this gives you the General Config of the camera 1:
> > http://HOSTNAME/SCRIPTNAME.php?c=1&a=GetGeneralConfig

> There is also a shortcut for GotoPreset for PTZ ( which is my most used function ), you can:
> >  http://HOSTNAME/SCRIPTNAME.php?c=CAM_NUMBER&p=PRESET_NUMBER

> Meaning this will set camera no.2 to PTZ preset 4
> >  http://HOSTNAME/SCRIPTNAME.php?c=2&p=4

## License
> As you can see in the script, I have not implement any of the config setting API calls or user actions for security reason. If you wish to use those, it should be relatively easy to implement.

> MIT license. Do whatever you want! ( and no evil! )
