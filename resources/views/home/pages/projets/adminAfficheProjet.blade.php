<div class="row g-5 mt--30" id="image-container">
    <!-- Start Single Card 1 -->
    @foreach ($projets as $index => $projet)
        @php
            $words = explode(' ', $projet->description);
            $firstFiveWords = implode(' ', array_slice($words, 0, 5));
        @endphp

        <div class="col-lg-4 col-md-6 col-sm-12 col-12 mt--30">
            <a href="{{ route('detail.projet', $projet->id) }}">
                <div class="rbt-card variation-02 rbt-hover">
                    <div class="rbt-card-img">
                        <a href="{{ route('detail.projet', $projet->id) }}">
                            <img src="{{ asset($projet->lien_photo) }}" style="height:250px;" alt="Card image">
                        </a>
                    </div>
                    <div class="rbt-card-body">
                        <h5 class="rbt-card-title">
                            <a href="{{ route('detail.projet', $projet->id) }}">{{ $projet->libelle }}</a>
                        </h5>
                        <div id="description-preview-{{ $index }}" style="display:block;">
                            {!! Str::limit($projet->description, 20) !!}
                            @if (count($words) > 10)
                                <a id="show-more-{{ $index }}" class="text-primary" style="cursor: pointer;"
                                    onclick="showMore({{ $index }})">
                                    Plus
                                </a>
                            @endif
                        </div>
                        <div id="description-full-{{ $index }}" style="display:none;">
                            {!! $projet->description !!}
                            <a id="show-more-{{ $index }}" class="text-primary" style="cursor: pointer;"
                                onclick="moinMore({{ $index }})">
                                Moins
                            </a>
                        </div>
                        {{-- <p class="rbt-card-text">{!! ($projet->description) !!}
                            <a href="{{ route('detail.projet', $projet->id) }}" class="text-primary">En savoir plus</a>
                        </p> --}}
                    </div>
                </div>

            </a>
        </div>
    @endforeach
    @if ($nombre = $projets->count() > 3)
        <center>
            <div class="button-group mt--30">
                <button class="rbt-btn btn-gradient rbt-marquee-btn radius-round" id="show-more-button">
                    <span data-text="VOIR TOUS">VOIR TOUS</span>
                </button>
            </div>
        </center>
    @endif
    {{-- @php
    use Carbon\Carbon;
        // dd(Carbon::now()->addDay()->format('Y-m-d'));
    @endphp --}}
</div>
@push('js')
    <script>
        function showMore(index) {
            document.getElementById('description-preview-' + index).style.display = 'none';
            document.getElementById('description-full-' + index).style.display = 'block';
        }

        function moinMore(index) {
            document.getElementById('description-preview-' + index).style.display = 'block';
            document.getElementById('description-full-' + index).style.display = 'none';
        }
    </script>
@endpush
