<!-- main-header opened -->
			<div class="main-header sticky side-header nav nav-item">
				<div class="container-fluid">
					<div class="main-header-left ">
						<div class="responsive-logo">
							{{-- logo --}}
						</div>
						<div class="app-sidebar__toggle" data-toggle="sidebar">
							<a class="open-toggle" href="#"><i class="header-icon fe fe-align-left" ></i></a>
							<a class="close-toggle" href="#"><i class="header-icons fe fe-x"></i></a>
						</div>
						<div class="main-header-center mr-3 d-sm-none d-md-none d-lg-block">
							<input class="form-control" placeholder="Search for anything..." type="search"> <button class="btn"><i class="fas fa-search d-none d-md-block"></i></button>
						</div>
					</div>
					<div class="main-header-right">
						<div class="nav nav-item  navbar-nav-right ml-auto">
							<div class="nav-link" id="bs-example-navbar-collapse-1">
								<form class="navbar-form" role="search">
									<div class="input-group">
										<input type="text" class="form-control" placeholder="Search">
										<span class="input-group-btn">
											<button type="reset" class="btn btn-default">
												<i class="fas fa-times"></i>
											</button>
											<button type="submit" class="btn btn-default nav-link resp-btn">
												<svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
											</button>
										</span>
									</div>
								</form>
							</div>
							<div class="dropdown nav-item main-header-notification">
								@can('notifications')
								<a class="new nav-link" href="#">
									<svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
									@if (auth()->user()->unreadNotifications->count() > 0)
										<span class=" pulse"></span>
									@endif
								</a>
								@endcan								
								<div class="dropdown-menu" id="app">
									<div class="menu-header-content bg-primary text-right">
										<div class="d-flex" id="notification_count">
											<h6 class="dropdown-title mb-1 tx-15 text-white font-weight-semibold mb-2">الإشعارات</h6>
											@if (auth()->user()->unreadNotifications->count() > 0)
												<a class="badge badge-pill badge-warning float-left mr-auto my-auto" href="{{route('all-as-read')}}">
													تحديد الكل كمقروء
												</a>
											@endif											
										</div>
										<p class="dropdown-title-text subtext mb-0 text-white op-6 pb-0 tx-12 ">لديك {{auth()->user()->unreadNotifications->count()}}  إشعار غير مقروء
									</div>
									<div class="main-notification-list Notification-scroll" id="notification">
										@foreach (Auth::user()->unreadNotifications as $notification)
										<a class="d-flex p-3 border-bottom" 
										href="{{auth()->user()->can('view invoice details') ?  route('invoice_details.show', $notification->data['id']) : '#'}}" onclick="readNotification('{{$notification->id}}')">
											<div class="notifyimg w-10 h-8 bg-pink p-0">
												<i class="la la-file-alt text-white mt-1"></i>
											</div>
											<div class="mr-3">
												<h5 class="notification-label mb-1">{{$notification->data['title']}}</h5>
												<h4 class="notification-label  mb-1">بواسطة: {{$notification->data['user']}}</h>
												<div class="notification-subtext">{{$notification->created_at->diffForHumans()}}</div>
											</div>
											<div class="mr-auto" >
												<i class="las la-angle-left text-left text-muted"></i>
											</div>
										</a>		
										@endforeach
									</div>
									<div class="dropdown-footer">
										<a href="#">VIEW ALL</a>
									</div>
								</div>
							</div>
							<div class="nav-item full-screen fullscreen-button">
								<a class="new nav-link full-screen-link" href="#"><svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-maximize"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg></a>
							</div>
							<div class="dropdown main-profile-menu nav nav-item nav-link">
								<a class="profile-user d-flex" href=""><img alt="" src="{{URL::asset('assets/img/faces/6.jpg')}}"></a>
								<div class="dropdown-menu">
									<div class="main-header-profile bg-primary p-3">
										<div class="d-flex wd-100p">
											<div class="main-img-user"><img alt="" src="{{URL::asset('assets/img/faces/6.jpg')}}" class=""></div>
											<div class="mr-3 my-auto">
												<h6>{{Auth::user()->name}}</h6><span>{{Auth::user()->email}}</span>
											</div>
										</div>
									</div>
									<a class="dropdown-item" href="{{ route('logout') }}"
									onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i
									class="bx bx-log-out"></i>تسجيل خروج</a>
									<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
									@csrf
									</form>
								</div>
							</div>
							<div class="dropdown main-header-message right-toggle">
								<a class="nav-link pr-0" data-toggle="sidebar-left" data-target="#">
									<svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
<!-- /main-header -->
<script>
	function readNotification(id)
	{
		$.ajax({
			url: "{{ URL::to('read-notification') }}/" + id,
			type: "GET",
			dataType: "json",
			success: function(data) {
				console.log('read');
			},
		});
	}
</script>
