@extends('layouts.message', ['title' => 'Liste des Messages', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Messages'])
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/select2.min.css') }}">
@endpush
@section('content')
    <div class="chat-app">
        @php
            
        @endphp
        <div class="d-flex flex-nowrap">
            <div class="order-1">
                <div class="c-list">
                    {{-- <div class="input-group mb-2">
                        <input type="text" class="form-control mb-1" placeholder="Recherche..." id="searchInput">
                    </div> --}}
                    <ul class="nav nav-tabs tab-page-toolbar rounded text-center mb-1" role="tablist">
                        <li class="flex-fill nav-item"><a class="nav-link border-0 active" data-bs-toggle="tab"
                                href="#chat-recent" role="tab" aria-selected="true"><i class="fa fa-envelope"></i>
                                Messages</a></li>
                        <li class="flex-fill nav-item"><a class="nav-link border-0" data-bs-toggle="tab"
                                href="#nouveau-message" role="tab" aria-selected="false"><i class="fa fa-send"></i>
                                Nouveau</a></li>
                    </ul>
                </div>
                @include('dashboard.messages.liste', ['messages' => $messages])
            </div>
        </div>
    </div>
    <script>
        $('.chat-app .chatlist-toggle').on('click', function() {
            $('.chat-app .order-1').toggleClass('open');
        });
    </script>
@endsection
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const messageList = document.getElementById('messageList');
            const messages = messageList.getElementsByTagName('li');
            searchInput.addEventListener('input', function() {
                const filter = searchInput.value.toLowerCase();
                Array.from(messages).forEach(function(message) {
                    const text = message.innerText.toLowerCase();
                    if (text.includes(filter)) {
                        message.style.display = '';
                    } else {
                        message.style.display = 'none';
                    }
                });
            });
        });
    </script>
    {{-- pour rendre le bouton dynamique --}}
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const tabMessages = document.getElementById('tab-messages');
            const tabNouveau = document.getElementById('tab-nouveau');
            const chatRecent = document.getElementById('chat-recent');
            const nouveauMessage = document.getElementById('nouveau-message');

            // Check localStorage for the last active tab
            const activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                // Remove existing active/show classes
                document.querySelector('.tab-pane.active').classList.remove('active', 'show');
                document.querySelector('.nav-link.active').classList.remove('active');

                // Add active/show classes to the saved tab
                document.querySelector(activeTab).classList.add('active', 'show');
                document.querySelector(`a[href="${activeTab}"]`).classList.add('active');
            } else {
                // Set default active tab
                chatRecent.classList.add('active', 'show');
                tabMessages.classList.add('active');
            }

            // Add event listeners to tabs to save active tab to localStorage
            tabMessages.addEventListener('click', () => {
                localStorage.setItem('activeTab', '#chat-recent');
            });

            tabNouveau.addEventListener('click', () => {
                localStorage.setItem('activeTab', '#nouveau-message');
            });
        });
    </script>
@endpush
