<?php
/*

fm_analytics - https://github.com/fantasticmachine/fm_analytics
------------
Author: Pete Eveleigh / Fantastic Machine
Twitter: @foamcow / @fmachine
Copyright © 2013 Pete Eveleigh | BSD & MIT license


Pikaday - https://github.com/dbushell/Pikaday
------------
Pikaday date picker provided by David Bushell
http://dbushell.com](http://dbushell.com/
Copyright © 2012 David Bushell | BSD & MIT license
*/


if ($CurrentUser->logged_in()) {
  $this->register_app('fm_analytics', 'Google Analytics', 5, 'Provides a basic analytics report on the dashboard', '0.5', true);
  $this->require_version('fm_analytics', '2.0.8');
  $this->add_setting('fm_analytics_ooid', 'OOcharts ID', 'text', '');
  $this->add_setting('fm_analytics_gaid', 'Google Analytics Profile ID', 'text', '');
}
?>