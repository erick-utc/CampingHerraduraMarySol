<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('User management')]
class Index extends Component
{
    /**
     * All of the columns that we will display in the table. We dynamically
     * pull them from the database so that adding a new field is automatic.
     * The only column we explicitly remove is the primary key since the
     * user asked not to show the ID in the table. We also hide a few
     * sensitive attributes that should not be editable through this UI.
     *
     * @var array<int,string>
     */
    public array $columns = [];

    /**
     * Cached collection of users. Because we reload the list after each
     * mutation, we simply store the Eloquent collection here for the
     * blade template to iterate over.
     *
     * @var \Illuminate\Database\Eloquent\Collection<int, User>
     */
    public $users;

    /**
     * The editable attributes for the currently selected user. Stored as
     * an array so that Livewire's `set()` helper can modify individual
     * keys during tests and in the browser.
     *
     * @var array<string,mixed>
     */
    public array $editing = [];

    /**
     * Model that is being edited (or null when creating a new user).
     * We don't bind to it directly in the template to avoid the Livewire
     * "Can't set model properties directly" error seen in tests.
     *
     * @var ?\App\Models\User
     */
    public ?User $selectedUser = null;

    public bool $showEditModal = false;
    public bool $showDeleteModal = false;

    /**
     * The user that we intend to delete. Stored separately because the
     * user might close the modal before the deletion occurs.
     *
     * @var ?\App\Models\User
     */
    public ?User $userToDelete = null;

    /**
     * Initialize the component state.
     */
    public function mount(): void
    {
        // only users with the "ver usuarios" permission may access this page
        abort_unless(auth()->user()->can('ver usuarios'), 403);

        $this->columns = collect(Schema::getColumnListing('users'))
            ->reject(fn ($column) => in_array($column, [
                'id',
                'password',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'remember_token',
                'email_verified_at',
                'two_factor_confirmed_at',
                'created_at',
                'updated_at',
            ]))
            ->toArray();

        $this->loadUsers();

        // start with an empty array so livewire can fill in individual
        // fields via `editing.*` binding.
        $this->editing = [];
    }

    /**
     * Fetch all users from the database.
     */
    protected function loadUsers(): void
    {
        $this->users = User::all();
    }

    /**
     * Begin editing a given user.
     */
    public function edit(User $user): void
    {
        abort_unless(auth()->user()->can('editar usuarios'), 403);

        $this->selectedUser = $user;
        $this->editing = $user->only($this->columns);
        $this->showEditModal = true;
    }

    /**
     * Persist the edited user back to the database.
     */
    public function save(): void
    {
        $validated = $this->validate($this->rules());

        // `validate` will return an array with the `editing` key because the
        // rules target the nested property. We only care about the inner data.
        $data = $validated['editing'] ?? [];

        if ($this->selectedUser) {
            $this->selectedUser->fill($data);
            $this->selectedUser->save();
        }

        $this->showEditModal = false;
        $this->loadUsers();
    }

    /**
     * Show a confirmation dialog before deleting.
     */
    public function confirmDelete(User $user): void
    {
        abort_unless(auth()->user()->can('borrar usuarios'), 403);

        $this->userToDelete = $user;
        $this->showDeleteModal = true;
    }

    /**
     * Delete the previously confirmed user.
     */
    public function deleteUser(): void
    {
        abort_unless(auth()->user()->can('borrar usuarios'), 403);

        if ($this->userToDelete) {
            $this->userToDelete->delete();
            $this->showDeleteModal = false;
            $this->loadUsers();
        }
    }

    /**
     * Validation rules for the form: every column is required except email
     * which also needs to be unique (ignoring the currently edited record).
     *
     * @return array<string,string>
     */
    protected function rules(): array
    {
        $rules = [];

        foreach ($this->columns as $column) {
            $key = "editing.$column";

            if ($column === 'email') {
                // email must always be present and valid
                $rules[$key] = 'required|email:rfc,dns|unique:users,email'
                    . ($this->selectedUser ? ',' . $this->selectedUser->id : '');
            } else {
                // the rest of the columns are optional; null values are fine
                $rules[$key] = 'nullable';
            }
        }

        return $rules;
    }

    public function render()
    {
        return view('livewire.users.index');
    }
}
