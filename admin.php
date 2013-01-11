<?php
if ($CurrentUser->logged_in()) {
  $this->register_app('fm_analytics', 'Google Analytics', 5, 'Provides a basic analytics report on the dashboard', '0.5', true);
  $this->require_version('fm_analytics', '2.0.8');
  $this->add_setting('fm_analytics_ooid', 'OOcharts ID', 'text', '');
  $this->add_setting('fm_analytics_gaid', 'Google Analytics Profile ID', 'text', '');
}
?>