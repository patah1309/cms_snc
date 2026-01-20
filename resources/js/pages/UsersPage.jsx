import React, { useEffect, useState } from 'react';
import Swal from 'sweetalert2';
import { ICONS } from '../components/IconMap';
import { swalDefaults } from '../utils/swal';

const ROLE_OPTIONS = [
    { value: 'super_admin', label: 'Super Admin' },
    { value: 'admin', label: 'Admin' },
    { value: 'user', label: 'User' },
];

export default function UsersPage({ authApi }) {
    const [users, setUsers] = useState([]);
    const [userForm, setUserForm] = useState({
        name: '',
        email: '',
        password: '',
        role: 'user',
    });
    const [editingId, setEditingId] = useState(null);

    useEffect(() => {
        authApi.get('/admin/users')
            .then((res) => setUsers(res.data.users || []))
            .catch(() => setUsers([]));
    }, [authApi]);

    const openCreate = () => {
        setEditingId(null);
        setUserForm({
            name: '',
            email: '',
            password: '',
            role: 'user',
        });
    };

    const openEdit = (user) => {
        setEditingId(user.id);
        setUserForm({
            name: user.name || '',
            email: user.email || '',
            password: '',
            role: user.role || 'user',
        });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            if (editingId) {
                const res = await authApi.put(`/admin/users/${editingId}`, {
                    name: userForm.name,
                    email: userForm.email,
                    password: userForm.password || undefined,
                    role: userForm.role,
                });
                setUsers((prev) =>
                    prev.map((row) => (row.id === editingId ? res.data.user : row))
                );
            } else {
                const res = await authApi.post('/admin/users', userForm);
                setUsers((prev) => [...prev, res.data.user]);
            }
            const modalEl = document.getElementById('userModal');
            if (window.bootstrap && modalEl) {
                window.bootstrap.Modal.getOrCreateInstance(modalEl).hide();
            }
        } catch (err) {
            const errorMessage = err?.response?.data?.message || 'Gagal menyimpan user.';
            await Swal.fire({
                ...swalDefaults,
                icon: 'error',
                title: 'Gagal',
                text: errorMessage,
                confirmButtonText: 'OK',
            });
        }
    };

    return (
        <div className="content">
            <div className="page-header">
                <h1 className="with-icon">
                    <span className="icon">{ICONS.users}</span>
                    Users
                </h1>
                <p>Tambah user baru dan atur peran.</p>
            </div>
            <div className="panel">
                <div className="section-toolbar">
                    <h3>Daftar User</h3>
                    <button
                        className="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#userModal"
                        onClick={openCreate}
                    >
                        <i className="fa-solid fa-plus"></i> Tambah User
                    </button>
                </div>
                <div className="table-responsive">
                    <table className="table table-hover align-middle">
                        <thead className="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Gabung</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {users.map((user) => (
                                <tr key={user.id}>
                                    <td><strong>{user.name}</strong></td>
                                    <td className="muted">{user.email}</td>
                                    <td className="muted">{user.role}</td>
                                    <td className="muted">{user.created_at?.slice(0, 10) || '-'}</td>
                                    <td>
                                        <div className="btn-group">
                                            <button
                                                className="btn btn-outline-secondary btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#userModal"
                                                onClick={() => openEdit(user)}
                                            >
                                                <i className="fa-regular fa-pen-to-square"></i>
                                            </button>
                                            <button
                                                className="btn btn-outline-danger btn-sm"
                                                onClick={async () => {
                                                    try {
                                                        await authApi.delete(`/admin/users/${user.id}`);
                                                        setUsers((prev) => prev.filter((row) => row.id !== user.id));
                                                    } catch (err) {
                                                        const errorMessage = err?.response?.data?.message || 'Gagal menghapus user.';
                                                        await Swal.fire({
                                                            ...swalDefaults,
                                                            icon: 'error',
                                                            title: 'Gagal',
                                                            text: errorMessage,
                                                            confirmButtonText: 'OK',
                                                        });
                                                    }
                                                }}
                                            >
                                                <i className="fa-regular fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                            {users.length === 0 && (
                                <tr>
                                    <td colSpan="5" className="text-center text-muted">Belum ada user.</td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
            <div className="modal fade" id="userModal" tabIndex="-1" aria-hidden="true">
                <div className="modal-dialog">
                    <div className="modal-content">
                        <form onSubmit={handleSubmit}>
                            <div className="modal-header">
                                <h5 className="modal-title">{editingId ? 'Edit User' : 'Tambah User'}</h5>
                                <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div className="modal-body">
                                <div className="mb-3">
                                    <label className="form-label">Nama</label>
                                    <input
                                        className="form-control"
                                        value={userForm.name}
                                        onChange={(e) => setUserForm((prev) => ({ ...prev, name: e.target.value }))}
                                        required
                                    />
                                </div>
                                <div className="mb-3">
                                    <label className="form-label">Email</label>
                                    <input
                                        className="form-control"
                                        type="email"
                                        value={userForm.email}
                                        onChange={(e) => setUserForm((prev) => ({ ...prev, email: e.target.value }))}
                                        required
                                    />
                                </div>
                                <div className="mb-3">
                                    <label className="form-label">Password</label>
                                    <input
                                        className="form-control"
                                        type="password"
                                        value={userForm.password}
                                        onChange={(e) => setUserForm((prev) => ({ ...prev, password: e.target.value }))}
                                        placeholder={editingId ? 'Kosongkan jika tidak diganti' : 'Minimal 8 karakter'}
                                        required={!editingId}
                                    />
                                </div>
                                <div className="mb-3">
                                    <label className="form-label">Role</label>
                                    <select
                                        className="form-select"
                                        value={userForm.role}
                                        onChange={(e) => setUserForm((prev) => ({ ...prev, role: e.target.value }))}
                                    >
                                        {ROLE_OPTIONS.map((role) => (
                                            <option key={role.value} value={role.value}>{role.label}</option>
                                        ))}
                                    </select>
                                </div>
                            </div>
                            <div className="modal-footer">
                                <button type="button" className="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" className="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    );
}
