@extends('layouts.app')

@section('content')
<div class="container">
	<div class="card">
		<div class="container-fliud">
			<div class="wrapper row">
				<div class="preview col-md-6">
					
					<div class="preview-pic">
					  <img src="{{ $film->photo }}" />
					</div>					
				</div>
				<div class="details col-md-6">
					<h3 class="product-title">{{ $film->name }}</h3>
					<div class="rating">
						<div class="stars">
							@php $rating = (int) $film->rating; @endphp
							@for($i=0; $i < $rating; $i++)
							<span class="glyphicon glyphicon-star"></span>
							@endfor
						</div>
						<span class="review-no">41 reviews</span>
					</div>
					<p class="product-description">{{ $film->description }}</p>
					<h4 class="price">Ticket price: <span>{{ $film->ticket_price }}</span></h4>
					<h4 class="price">Release Date: <span>{{ $film->release_date }}</span></h4>
					<h4 class="price">Country: <span>{{ $film->country }}</span></h4>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="blog-comment">
						<h3 class="text-success">Comments</h3>
						<hr/>
						<ul class="comments">
							@foreach($film->comments as $comment)
							<li class="clearfix">
								<div class="post-comments">
									<p class="meta">{{ date('Y-m-d', strtotime($comment->created_at)) }} <a href="javascript:;">{{ $comment->user->name }}</a> says : <i class="pull-right"></i></p>
									<p>
									{{ $comment->body }}
									</p>
								</div>
							</li>
							@endforeach
						</ul>

						@if(!Auth::guest())
						@if(session()->has('message.level'))
						    <div class="alert alert-info"> 
						    {{ session('message') }}
						    </div>
						@endif
						<form method="post" action="{{ route('films.comment.store') }}">
							{{ csrf_field() }}
							<div class="col-md-8 form-group">
				                <textarea name="body" class="form-control" id="body" rows="5" cols="100">
				                </textarea>
				            </div>
				            <div class="clearfix"></div>
				            <div class="col-md-6 form-group">
				                <button type="submit" class="btn btn-primary">Commnet</button>
				            </div>
				            <input type="hidden" name="film" value="{{ $film->slug }}">
						</form>
						@else
						<a href="{{ route('login') }}">Please sign in to add comment</a>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
