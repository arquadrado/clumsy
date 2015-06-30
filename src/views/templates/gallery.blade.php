<div class="row">
	@foreach ($items as $item)
		<div class="col-md-3">
			<a href="{{ route("$admin_prefix.$resource.edit", $item->id) }}">
				<div class="col-md-12 thumbnail gallery-item">
					<img src="{{ $item->mediaPath('image') }}" class="img-responsive" alt="image">
					<div class="caption">
						@foreach ($columns as $column => $name)
		                    <p>{{ $item->$column }}</p>
		                @endforeach
					</div>
				</div>
			</a>
		</div>
    @endforeach
</div>