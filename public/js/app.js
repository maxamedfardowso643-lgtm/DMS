/* ---------- Bootstrap 5 compatibility shim ----------
   Bootstrap 5 dropped the jQuery plugin API ($(...).modal()/.dropdown()/.tab()).
   All view JS in this app still calls the old jQuery-style API, so we restore it
   here rather than rewriting every call site. Without this, calling $('#x').modal('show')
   throws "modal is not a function" and silently kills the rest of that callback
   (which is why Save/Edit/Close/dropdowns appeared "broken"). */
(function () {
    if (typeof jQuery === 'undefined' || typeof bootstrap === 'undefined') return;

    function wire(name, Ctor) {
        jQuery.fn[name] = function (action, ...args) {
            return this.each(function () {
                const instance = Ctor.getOrCreateInstance(this);
                if (typeof action === 'string' && typeof instance[action] === 'function') {
                    instance[action](...args);
                }
            });
        };
    }

    wire('modal', bootstrap.Modal);
    wire('dropdown', bootstrap.Dropdown);
    wire('tab', bootstrap.Tab);
    wire('tooltip', bootstrap.Tooltip);
    wire('popover', bootstrap.Popover);

    // data-bs-dismiss="modal" / data-bs-toggle="modal|dropdown|tab" are handled
    // natively by Bootstrap 5's own delegated listeners once the attributes are
    // correct — no extra wiring needed for those.
})();

/* ---------- Toast notifications (SweetAlert2-backed, keeps the toastr.* API) ---------- */
const toastMixin = typeof Swal !== 'undefined' ? Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3800,
    timerProgressBar: true,
    didOpen: (t) => {
        t.addEventListener('mouseenter', Swal.stopTimer);
        t.addEventListener('mouseleave', Swal.resumeTimer);
    },
}) : null;

window.toastr = {
    options: {},
    success: (message, title) => toastMixin && toastMixin.fire({ icon: 'success', title: title || message, text: title ? message : undefined }),
    error: (message, title) => toastMixin && toastMixin.fire({ icon: 'error', title: title || message, text: title ? message : undefined }),
    info: (message, title) => toastMixin && toastMixin.fire({ icon: 'info', title: title || message, text: title ? message : undefined }),
    warning: (message, title) => toastMixin && toastMixin.fire({ icon: 'warning', title: title || message, text: title ? message : undefined }),
};

/* ---------- Delete confirmation ---------- */
function confirmDelete(url, onSuccess) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: 'DELETE',
                success: function (res) {
                    toastr.success(res.message || 'Deleted successfully');
                    if (onSuccess) onSuccess(res);
                },
                error: function (xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Delete failed');
                },
            });
        }
    });
}

function showFormErrors(form, errors) {
    form.find('.is-invalid').removeClass('is-invalid');
    form.find('.invalid-feedback').remove();
    $.each(errors, function (field, messages) {
        const input = form.find(`[name="${field}"]`);
        input.addClass('is-invalid');
        input.after(`<div class="invalid-feedback">${messages[0]}</div>`);
    });
}

/* ---------- Theme (light/dark) ---------- */
const Theme = {
    key: 'dcatms-theme',
    get() {
        return localStorage.getItem(this.key) || 'light';
    },
    apply(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        $('.theme-toggle-icon').attr('class', 'theme-toggle-icon fas ' + (theme === 'dark' ? 'fa-sun' : 'fa-moon'));
    },
    toggle() {
        const next = this.get() === 'dark' ? 'light' : 'dark';
        localStorage.setItem(this.key, next);
        this.apply(next);
    },
    init() {
        this.apply(this.get());
    },
};

$(function () {
    Theme.init();
    $(document).on('click', '.theme-toggle-btn', function (e) {
        e.preventDefault();
        Theme.toggle();
    });
});

/* ---------- Print a specific open modal without printing the rest of the app ---------- */
function printModal() {
    document.body.classList.add('printing-modal');
    window.print();
    setTimeout(() => document.body.classList.remove('printing-modal'), 300);
}

/* ---------- Reusable patient search (used by Appointments / Invoices / Payments) ---------- */
function initPatientSearch(inputSelector, resultsSelector, onSelect) {
    let searchTimer;
    const $input = $(inputSelector);
    const $results = $(resultsSelector);

    $input.on('input', function () {
        clearTimeout(searchTimer);
        const q = $(this).val().trim();
        if (q.length < 2) {
            $results.hide().empty();
            return;
        }
        searchTimer = setTimeout(() => {
            $.get('/patients-search', { q }, function (patients) {
                $results.empty();
                if (!patients.length) {
                    $results.append('<div class="search-result-empty">No patients found</div>').show();
                    return;
                }
                patients.forEach((p) => {
                    $results.append(`
                        <button type="button" class="search-result-item" data-id="${p.id}">
                            <span class="sr-name">${p.name}</span>
                            <span class="sr-meta">${p.code} &middot; ${p.phone || 'no phone'}</span>
                        </button>
                    `);
                });
                $results.show();
            });
        }, 300);
    });

    $(document).on('click', `${resultsSelector} .search-result-item`, function () {
        const id = $(this).data('id');
        const patient = { id, name: $(this).find('.sr-name').text(), meta: $(this).find('.sr-meta').text() };
        $input.val(patient.name);
        $results.hide().empty();
        onSelect(patient);
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest(inputSelector).length && !$(e.target).closest(resultsSelector).length) {
            $results.hide();
        }
    });
}
