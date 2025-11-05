/**
 * WordCamp Notes Admin JavaScript
 */

(function($) {
    'use strict';

    const API = {
        baseUrl: wcNotesData.restUrl,
        nonce: wcNotesData.nonce,
        
        async fetchNotes() {
            try {
                const response = await fetch(this.baseUrl + 'notes');
                return await response.json();
            } catch (error) {
                console.error('Error fetching notes:', error);
                throw error;
            }
        },

        async createNote(noteData) {
            const response = await fetch(this.baseUrl + 'notes', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': this.nonce
                },
                body: JSON.stringify(noteData)
            });
            return await response.json();
        },

        async updateNote(id, noteData) {
            const response = await fetch(this.baseUrl + 'notes/' + id, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': this.nonce
                },
                body: JSON.stringify(noteData)
            });
            return await response.json();
        },

        async deleteNote(id) {
            const response = await fetch(this.baseUrl + 'notes/' + id, {
                method: 'DELETE',
                headers: {
                    'X-WP-Nonce': this.nonce
                }
            });
            return await response.json();
        }
    };

    function displayNotes(notes) {
        const list = $('#notes-list');
        list.empty();

        if (notes.length === 0) {
            list.html('<p>' + wcNotesData.strings?.noNotes || 'No notes found.' + '</p>');
            return;
        }

        const html = notes.map(note => `
            <div class="wc-note-item" data-id="${note.id}">
                <h3>${escapeHtml(note.title)}</h3>
                <p>${escapeHtml(note.content)}</p>
                <div class="wc-note-actions">
                    <button class="button edit-note" data-id="${note.id}">Edit</button>
                    <button class="button delete-note" data-id="${note.id}">Delete</button>
                </div>
            </div>
        `).join('');

        list.html(html);
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function showMessage(message, type = 'success') {
        const msgDiv = $('#wc-notes-message');
        msgDiv.removeClass('notice-success notice-error')
              .addClass('notice-' + type)
              .html('<p>' + escapeHtml(message) + '</p>')
              .show();

        setTimeout(() => {
            msgDiv.fadeOut();
        }, 3000);
    }

    async function loadNotes() {
        try {
            const notes = await API.fetchNotes();
            displayNotes(notes);
        } catch (error) {
            showMessage('Error loading notes: ' + error.message, 'error');
        }
    }

    function showForm(note = null) {
        const form = $('#notes-form');
        const formTitle = $('#form-title');
        const noteId = $('#note-id');
        const noteTitle = $('#note-title');
        const noteContent = $('#note-content');

        if (note) {
            formTitle.text('Edit Note');
            noteId.val(note.id);
            noteTitle.val(note.title);
            noteContent.val(note.content);
        } else {
            formTitle.text('Add New Note');
            noteId.val('');
            noteTitle.val('');
            noteContent.val('');
        }

        form.slideDown();
    }

    function hideForm() {
        $('#notes-form').slideUp();
        $('#note-form')[0].reset();
    }

    // Event handlers
    $(document).ready(function() {
        loadNotes();

        $('#add-note-btn').on('click', function() {
            showForm();
        });

        $('#cancel-form').on('click', function() {
            hideForm();
        });

        $('#note-form').on('submit', async function(e) {
            e.preventDefault();

            const noteId = $('#note-id').val();
            const noteData = {
                title: $('#note-title').val(),
                content: $('#note-content').val()
            };

            try {
                if (noteId) {
                    await API.updateNote(noteId, noteData);
                    showMessage('Note updated successfully!');
                } else {
                    await API.createNote(noteData);
                    showMessage('Note created successfully!');
                }
                hideForm();
                loadNotes();
            } catch (error) {
                showMessage('Error saving note: ' + error.message, 'error');
            }
        });

        $(document).on('click', '.edit-note', function() {
            const noteId = $(this).data('id');
            const noteItem = $(this).closest('.wc-note-item');
            const note = {
                id: noteId,
                title: noteItem.find('h3').text(),
                content: noteItem.find('p').text()
            };
            showForm(note);
        });

        $(document).on('click', '.delete-note', async function() {
            if (!confirm('Are you sure you want to delete this note?')) {
                return;
            }

            const noteId = $(this).data('id');
            try {
                await API.deleteNote(noteId);
                showMessage('Note deleted successfully!');
                loadNotes();
            } catch (error) {
                showMessage('Error deleting note: ' + error.message, 'error');
            }
        });

        $('#search-notes').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('.wc-note-item').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.includes(searchTerm));
            });
        });
    });

})(jQuery);

