<div class="tab-pane fade " id="nouveau-message" role="tabpanel">
    <ul class="list-unstyled list-group list-group-custom list-group-flush mb-0">
        <li class="list-group-item">
            <a href="{{ route('message.nouveauAutre') }}" class="d-flex">
                <i class="fa fa-envelope"></i>
                <div class="text-muted">
                    <span class="mx-2">Envoyer par email a un particulier</span>
                </div>
            </a>
        </li>
        <li class="list-group-item">
            <a href="{{ route('message.nouveauMutualiste') }} " class="d-flex">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-person-fill-up" viewBox="0 0 16 16">
                    <path
                        d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.354-5.854 1.5 1.5a.5.5 0 0 1-.708.708L13 11.707V14.5a.5.5 0 0 1-1 0v-2.793l-.646.647a.5.5 0 0 1-.708-.708l1.5-1.5a.5.5 0 0 1 .708 0M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                    <path
                        d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4" />
                </svg>
                <div class="text-muted">
                    <span class="mx-2">Ecrire a un Mutualiste</span>
                </div>
            </a>
        </li>
    </ul>
</div>
