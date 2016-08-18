@extends('layouts.app')

@section('content')
<div class="container page-content">
    <div class="row">
			<div class="ui items">
			  <div class="item">
			    <div class="image">
			      <img src="{{ asset('images/mourinho.jpg') }}">
			    </div>
			    <div class="content">
			      <a class="header">HOT</a>
			      <div class="meta">
			        <span>Mourinho signed to coach for Manchester United...</span>
			      </div>
			      <div class="description">
			       	<div class="ui star rating" data-rating="3"></div>
			      </div>
			      <div class="extra">
			        <button class="ui facebook button" 
			        onclick="(function(){$('.ui.modal').modal('show')}())">
			        	<i class="facebook icon"></i>
			        	Share
			        </button>
			        <button class="ui facebook button" 
			         onclick="(function(){$('.ui.modal').modal('show')}())">
			        	<i class="thumbs outline up icon"></i>
			        	Like
			        </button>
			      </div>
			    </div>
			  </div>
			  <div class="item">
			    <div class="image">
			     <img src="{{ asset('images/transfer.jpg') }}">
			    </div>
			    <div class="content">
			      <a class="header">HOT!!</a>
			      <div class="meta">
			        <span>Luis Suarez has moved to FC Barcelona today,.....</span>
			      </div>
			      <div class="description">
			        <div class="ui star rating" data-rating="3"></div>
			      </div>
			      <div class="extra">
			        <button class="ui facebook button" 
			        onclick="(function(){$('.ui.modal').modal('show')}())">
			        	<i class="facebook icon"></i>
			        	Share
			        </button>
			        <button class="ui facebook button" 
			         onclick="(function(){$('.ui.modal').modal('show')}())">
			        	<i class="thumbs outline up icon"></i>
			        	Like
			        </button>
			      </div>

			    </div>
			  </div>
			</div>
			<div class="ui modal segment">
				<div class="ui comments">
					 <h3 class="ui dividing header">Comments</h3>
					  <div class="comment">
					    <a class="avatar">
					      <img src="{{ asset('images/man.png') }}">
					    </a>
				    	<div class="content">
				      <a class="author">Matt</a>
				      <div class="metadata">
				        <span class="date">Today at 5:42PM</span>
				      </div>
				      <div class="text">
				        How artistic!
				      </div>
				      <div class="actions">
				        <a class="reply">Reply</a>
				      </div>
				    </div>
				  </div>
				  <div class="comment">
				    <a class="avatar">
				      <img src="{{ asset('images/man.png') }}">
				    </a>
				    <div class="content">
				      <a class="author">Elliot Fu</a>
				      <div class="metadata">
				        <span class="date">Yesterday at 12:30AM</span>
				      </div>
				      <div class="text">
				        <p>This has been very useful for my research. Thanks as well!</p>
				      </div>
				      <div class="actions">
				        <a class="reply">Reply</a>
				      </div>
				    </div>
				    <div class="comments">
				      <div class="comment">
				        <a class="avatar">
				         	<img src="{{ asset('images/young.png') }}">
				        </a>
				        <div class="content">
				          <a class="author">Jenny Hess</a>
				          <div class="metadata">
				            <span class="date">Just now</span>
				          </div>
				          <div class="text">
				            Elliot you are always so right :)
				          </div>
				          <div class="actions">
				            <a class="reply">Reply</a>
				          </div>
				        </div>
				      </div>
				    </div>
				  </div>
				  <div class="comment">
				    <a class="avatar">
				      <img src="{{ asset('images/young.png') }}">
				    </a>
				    <div class="content">
				      <a class="author">Joe Henderson</a>
				      <div class="metadata">
				        <span class="date">5 days ago</span>
				      </div>
				      <div class="text">
				        Dude, this is awesome. Thanks so much
				      </div>
				      <div class="actions">
				        <a class="reply">Reply</a>
				      </div>
				    </div>
				  </div>
				  <form class="ui reply form">
				    <div class="field">
				      <textarea></textarea>
				    </div>
				    <div class="ui blue labeled submit icon button">
				      <i class="icon edit"></i> Add Reply
				    </div>
				  </form>
				</div>	
			</div>
    </div>
 </div>   
	
@endsection