@extends('main')    
    @section('page-styles')
        {{ HTML::style('/assets/metronic/global/plugins/typeahead/typeahead.css') }}
        {{ HTML::style('//fonts.googleapis.com/css?family=Bitter') }}
        {{ HTML::style('/assets/metronic/frontend/layout/css/style.css') }}
        {{ HTML::style('/assets/metronic/frontend/layout/css/style-responsive.css') }}
        {{ HTML::style('/assets/metronic/frontend/layout/css/themes/red.css') }}
        
        {{ HTML::style('/assets/css/style_frontend.css') }}
    @stop

    @section('body')
        <body class="corporate">
            @section('header')
            <?php
                if (!isset($pageNo)) {
                    $pageNo = 0;
                } 
            ?>            
            <div class="header background-default">
                <div class="container">
                    <div class="row margin-top-sm">
                        <div class="col-sm-2 text-center padding-top-xs">
                            <a href="{{ URL::route('user.home') }}">
                                <img src="/assets/img/logo_company.png" alt="Finternet-Group" style="height: 30px; ">
                            </a>
                        </div>
                        <div class="col-sm-5">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="input-icon">
                                            <i class="fa fa-pencil" style="margin-top: 16px;"></i>
                                            <input type="text" class="form-control input-lg input-circle custom-typeahead" id="js-text-keyword" placeholder="Enter Keyword" value="{{ Session::get('keyword') }}">
                                        </div>
                                    </div>                                
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="input-icon">
                                            <i class="fa fa-map-marker" style="margin-top: 16px;"></i>
                                            <input type="text" class="form-control input-lg input-circle custom-typeahead" id="js-text-location" placeholder="Helsinki, FI"  value="{{ Session::get('location') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-1 text-center">
                            <div class="form-group">
                                <button type="button" class="btn green btn-block btn-circle btn-lg" id="js-btn-search">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-sm-4 color-white padding-top-xs text-right">
                            @if (Session::has('user_id'))
                            <a href="{{ URL::route('user.cart') }}" class="text-shadow btn-menu {{ $pageNo == 1 ? 'active' : '' }}">Cart</a>
                            |
                            <a href="{{ URL::route('user.profile') }}" class="text-shadow btn-menu {{ $pageNo == 2 ? 'active' : '' }}">Profile</a>
                            |
                            <a href="{{ URL::route('user.offers') }}" class="text-shadow btn-menu {{ $pageNo == 3 ? 'active' : '' }}">Offers</a>
                            |
                            <a href="{{ URL::route('user.message') }}" class="text-shadow btn-menu {{ $pageNo == 4 ? 'active' : '' }}">Messages</a>
                            |                            
                            <a href="{{ URL::route('user.doSignout') }}" class="text-shadow btn-menu">Sign Out</a>
                            @else
                            <a href="{{ isset($redirect) && ($redirect != '') ? URL::route('user.login').'?redirect='.urlencode($redirect) : URL::route('user.login') }}" class="text-shadow btn-menu {{ $pageNo == 51 ? 'active' : '' }}">Log In</a>
                            |
                            <a href="{{ isset($redirect) && ($redirect != '') ? URL::route('user.signup').'?redirect='.urlencode($redirect) : URL::route('user.signup') }}" class="text-shadow btn-menu {{ $pageNo == 52 ? 'active' : '' }}">Sign Up</a>                            
                            |
                            <a href="{{ URL::route('company.auth') }}" class="text-shadow btn-menu">&nbsp;<i class="fa fa-building"></i>&nbsp;For Business</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="header-navigation font-transform-inherit search-navigation">
                    <ul class="text-center">
                        @foreach ($categories as $key => $value)
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#" style="color: #FFF;">
                                <i class="{{ $value->icon }}"></i>
                                <b>{{ $value->name }}</b> <span class="caret"></span>
                            </a>
                            
                            <ul class="dropdown-menu">
                                @foreach ($value->subCategories as $subKey => $subValue)
                                <li><a href="{{ URL::route('store.search').'?keyword='.$subValue->name }}">{{ $subValue->name }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        @endforeach
                    </ul>
                </div>    
                <div class="clearfix"></div>                
            </div>            
                      
            @show

            @section('main')
            
            @show

            @section('footer')
            <div class="pre-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-4 pre-footer-col">
                          <h2>Company Info</h2>
                          <ul>
                            <li><a href="http://finternet-group.com/about-us/" target="_blank">About Us</a></li>
                            <li><a href="http://finternet-group.com/blog-3/" target="_blank">Blog</a></li>
                            <li><a href="http://finternet-group.com/jobs/" target="_blank">Careers</a></li>
                            <li><a href="http://finternet-group.com/media-companies/" target="_blank">Media Companies</a></li>
                            <li><a href="http://finternet-group.com/investors/" target="_blank">Investors</a></li>
                            <li><a href="http://finternet-group.com/contact-us/" target="_blank">Contact &amp; Support</a></li>
                          </ul>
                        </div>
                      
                        <div class="col-sm-4 pre-footer-col">
                          <h2>Our Contacts</h2>
                          <address class="margin-bottom-40">
                          Finternet Group Oy<br>
                          Malmin kauppatie 8b<br>
                          00700 Helsinki, Finland<br>
                          Website: <a href="http://www.finternet-group.com" target="_blank">www.finternet-group.com</a><br>
                          Phone: +358 45 262 5977<br>
                          Email: mikael@finternet-group.com<br>
                          </address>
                        </div>
                        <div class="col-sm-4 pre-footer-col">
                            <div class="pre-footer-subscribe-box pre-footer-subscribe-box-vertical">
                                <h2>Newsletter</h2>
                                <p>Subscribe to our newsletter and stay up to date with the latest news and deals!</p>
                                <div class="input-group">
                                    <input type="text" placeholder="Enter your email..." class="form-control" id="js-text-subscriber-email">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="submit" id="js-btn-subscriber">Subscribe</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="footer">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 padding-top-10">
                        2015 &copy; Finternet-Group. ALL Rights Reserved.
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <ul class="social-footer list-unstyled list-inline pull-right">
                                <li><a href="https://www.facebook.com/FinternetGroup" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="https://www.linkedin.com/company/9221291?trk=tyah&trkInfo=idx%3A1-1-1%2CtarId%3A1424145037259%2Ctas%3Afinternet" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="https://twitter.com/finternetgroup" target="_blank"><i class="fa fa-twitter"></i></a></li>
                            </ul>  
                        </div>
                    </div>
                </div>
            </div>            
            <form method="get" action="{{ URL::route('store.search') }}" id="js-frm-search">
                <input type="hidden" name="keyword"/>
                <input type="hidden" name="location"/>
                <input type="hidden" name="lat"/>
                <input type="hidden" name="lng"/>
                <input type="hidden" name="dt"/>
                <input type="hidden" name="orderBy" value="1"/>
            </form>
            @show
        </body>
    @stop

    @section('page-scripts')
        {{ HTML::script('/assets/metronic/frontend/layout/scripts/back-to-top.js') }}
        {{ HTML::script('/assets/metronic/frontend/layout/scripts/layout.js') }}
        {{ HTML::script('/assets/metronic/global/plugins/typeahead/handlebars.min.js') }}
        {{ HTML::script('/assets/metronic/global/plugins/typeahead/typeahead.bundle.min.js') }}
        {{ HTML::script('//maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true') }}
        <script>
        var substringMatcher = function(strs) {
            return function findMatches(q, cb) {
                var matches, substrRegex;
                matches = [];
                substrRegex = new RegExp(q, 'i');
                $.each(strs, function(i, str) {
                    if (substrRegex.test(str)) {
                        matches.push({ value: str });
                    }
                });
                cb(matches);
            };
        };
           
        var cities = [];
        @foreach ($cities as $key => $value)
            cities[{{ $key }}] = '{{ $value->name }}';
        @endforeach

        var categories = [];
        @foreach ($categories as $key => $value)
            categories[categories.length] = '{{ $value->name }}';
            @foreach ($value->subCategories as $subKey => $subValue)
                categories[categories.length] = '{{ $subValue->name }}';
            @endforeach
        @endforeach            
           
        $('#js-text-keyword').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'keywords',
            displayKey: 'value',
            source: substringMatcher(categories)
        });
            
        $('#js-text-location').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'cities',
            displayKey: 'value',
            source: substringMatcher(cities)
        });
        
        $("#js-text-keyword, #js-text-location").keyup(function(event) {
            if (event.keyCode == 13) {
                $("button#js-btn-search").click();
            }
        });
        
        $("button#js-btn-search").click(function() {
            $("input[name='keyword']").val($("#js-text-keyword").val());
            $("input[name='location']").val($("#js-text-location").val());            
            $("#js-frm-search").submit();
        });

        $('[data-toggle="tooltip"]').tooltip();
        $('input#js-number-rating').rating();

        function validateEmail(email) {
            var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
            return re.test(email);
        }         
        
        $(document).ready(function() {
            
            $("div.thumb-store-item").click(function() {
                window.location.href = $(this).attr("data-href");
            });
                        
            if(navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    $("input[name='lat']").val(position.coords.latitude);
                    $("input[name='lng']").val(position.coords.longitude);
                });
            }

            $("button#js-btn-subscriber").click(function() {
                var email = $("input#js-text-subscriber-email").val();
                if (validateEmail(email)) {
                    $.ajax({
                        url: "{{ URL::route('async.user.doSubscriber') }}",
                        dataType : "json",
                        type : "POST",
                        data : { email : email },
                        success : function(result){
                            bootbox.alert(result.msg);
                            window.setTimeout(function(){
                                bootbox.hideAll();
                            }, 2000);
                        }
                    });
                } else {
                    bootbox.alert("The email format is invalid");
                    window.setTimeout(function(){
                        bootbox.hideAll();
                    }, 2000);                        
                }
            });            
        });
        </script>      
    @stop
@stop
