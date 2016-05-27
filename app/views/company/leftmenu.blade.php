<?php
    if (!isset($pageNo)) {
        $pageNo = 0;
    }
?>

<ul class="tabbable faq-tabbable margin-bottom-normal">
    <li class="{{ ($pageNo == 1) ? 'active' : '' }}"><a href="#">Dashboard</a></li>
    <li class="{{ ($pageNo == 2) ? 'active' : '' }}"><a href="#">Conumser Management</a></li>
    <li class="{{ ($pageNo == 3) ? 'active' : '' }}"><a href="#">Marketing Tools</a></li>
    <li class="{{ ($pageNo == 4) ? 'active' : '' }}"><a href="#">Comments Management</a></li>
    <li class="{{ ($pageNo == 5) ? 'active' : '' }}"><a href="#">Rating Management</a></li>
    <li class="{{ ($pageNo == 6) ? 'active' : '' }}"><a href="#">Feedback Management</a></li>
    <li class="{{ ($pageNo == 7) ? 'active' : '' }}"><a href="#">Offer Management</a></li>
    <li class="{{ ($pageNo == 8) ? 'active' : '' }}"><a href="#">Loyalty Management</a></li>
    <li class="{{ ($pageNo == 10) ? 'active' : '' }}"><a href="#">Settings</a></li>
    <li class="{{ ($pageNo == 9) ? 'active' : '' }}"><a href="{{ URL::route('company.auth.profile') }}">Company Profile</a></li>
</ul>
