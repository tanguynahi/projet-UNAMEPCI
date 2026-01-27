<div class="rbt-rbt-blog-area rbt-section-gapTop bg-color-white" id="projet">
    @if ($nombre = $projets->count() > null)
        <div class="container">
            <div class="row row--15 align-items-center mb--30">
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="section-title text-center">
                        <h2 class="title text-center">Nos Projets</h2>
                    </div>
                </div>
            </div>
            @include('home.pages.projets.adminAfficheProjet')
        </div>
    @endif
</div>
<script>
    // Ajoutez ce script à la fin de votre fichier HTML ou dans un fichier JS séparé
    document.getElementById('bouton-actualite').addEventListener('click', function() {
        var hiddenCards = document.querySelectorAll('.card-hidden');
        hiddenCards.forEach(function(card) {
            card.style.display = 'block';
        });
        this.style.display = 'none'; // Cacher le bouton après avoir affiché les cartes
    });
</script>
