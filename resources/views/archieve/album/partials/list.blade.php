<div class="row">
    @foreach ($albums as $album)
        <div class="col-md-3">
            <a href="{{ route('archieve.album.show', ['id' => $album->id]) }}" class="text-black text-truncate">
                <div class="card mb-4 box-shadow">
                    @if (!is_null($album->attachment))
                        <div style="width:100%;overflow:hidden">
                            <img src="{{ asset(json_decode($album->attachment)[0]->attachment) }}"
                                onerror="this.onerror=null;this.src='{{ asset('img/image-not-found.jpg') }}'"
                                width="100%" style="height:350px;object-fit: cover;" />
                        </div>
                    @else
                        <div style="width:100%;overflow:hidden">
                            <img class="card-img-top" src="{{ asset('img/image-not-found.jpg') }}"
                                data-holder-rendered="true" width="100%" style="height:350px;object-fit: cover;">
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <p class="card-text fw-bold my-1">{{ $album->name }}</p>
                            <div class="dropdown text-black">
                                <a class="text-black" id="dropdownMenuButton" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots"></i></a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#infoAlbum"
                                        onclick="getRecord({{ $album->id }}, 'info')" href="#">Informasi</a>
                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editAlbum"
                                        onclick="getRecord({{ $album->id }}, 'edit')" href="#">Ubah</a>
                                    <a class="dropdown-item" onclick="destroyRecord({{ $album->id }})"
                                        href="#">Hapus</a>
                                </div>
                            </div>
                        </div>
                        <div class="text-end mt-4">
                            <small class="text-muted">{{ date('d F Y', strtotime($album->date)) }}</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>
<div class="d-flex justify-content-center">
    {{ $albums->links() }}
</div>
<script>
    $('.pagination a').on('click', function(event) {
        event.preventDefault();

        $('li').removeClass('active');
        $(this).parent('li').addClass('active');

        let page = $(this).attr('href').split('page=')[1];
        getAlbum(page);
    });
</script>
