import React, { useEffect, useState } from 'react';
import Swal from 'sweetalert2';
import { ALL_MENUS } from '../constants/menus';
import { ICONS, getIcon } from '../components/IconMap';
import { swalDefaults } from '../utils/swal';

export default function AccessPage({ authApi }) {
    const [users, setUsers] = useState([]);
    const [selectedUserId, setSelectedUserId] = useState('');
    const [selectedPermissions, setSelectedPermissions] = useState({});
    const [loading, setLoading] = useState(false);
    const [query, setQuery] = useState('');
    const [page, setPage] = useState(1);
    const pageSize = 6;

    useEffect(() => {
        authApi.get('/users')
            .then((res) => setUsers(res.data.users || []))
            .catch(async (err) => {
                setUsers([]);
                const errorMessage = err?.response?.data?.message || 'Tidak bisa mengambil data user.';
                await Swal.fire({
                    ...swalDefaults,
                    icon: 'error',
                    title: 'Gagal',
                    text: errorMessage,
                    confirmButtonText: 'OK',
                });
            });
    }, [authApi]);

    useEffect(() => {
        setPage(1);
    }, [query]);

    useEffect(() => {
        if (!selectedUserId) {
            setSelectedPermissions({});
            return;
        }
        setLoading(true);
        authApi.get(`/users/${selectedUserId}/permissions`)
            .then((res) => setSelectedPermissions(res.data.permissions || {}))
            .finally(() => setLoading(false));
    }, [authApi, selectedUserId]);

    const updatePermission = (menu, field, value) => {
        setSelectedPermissions((prev) => ({
            ...prev,
            [menu]: {
                ...(prev[menu] || {}),
                [field]: value,
            },
        }));
    };

    const filteredUsers = users.filter((user) => {
        const q = query.toLowerCase();
        return (
            user.name.toLowerCase().includes(q) ||
            user.email.toLowerCase().includes(q)
        );
    });
    const totalPages = Math.max(1, Math.ceil(filteredUsers.length / pageSize));
    const clampedPage = Math.min(page, totalPages);
    const start = (clampedPage - 1) * pageSize;
    const pageUsers = filteredUsers.slice(start, start + pageSize);

    const handleSave = async () => {
        if (!selectedUserId) return;
        try {
            await authApi.put(`/users/${selectedUserId}/permissions`, {
                permissions: selectedPermissions,
            });
            await Swal.fire({
                ...swalDefaults,
                icon: 'success',
                title: 'Berhasil',
                text: 'Hak akses disimpan.',
                confirmButtonText: 'OK',
            });
        } catch (err) {
            const errorMessage = err?.response?.data?.message || 'Gagal menyimpan akses.';
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
                    <span className="icon">{ICONS.access}</span>
                    Hak Akses User
                </h1>
                <p>Atur akses menu per user.</p>
            </div>
            <div className="panel">
                <div className="datatable-controls">
                    <div>
                        <label>
                            Cari User
                            <input
                                value={query}
                                onChange={(e) => setQuery(e.target.value)}
                                placeholder="Nama atau email"
                            />
                        </label>
                    </div>
                    <div className="datatable-pagination">
                        <button
                            className="ghost"
                            disabled={clampedPage === 1}
                            onClick={() => setPage((p) => Math.max(1, p - 1))}
                        >
                            Prev
                        </button>
                        <span>{clampedPage} / {totalPages}</span>
                        <button
                            className="ghost"
                            disabled={clampedPage === totalPages}
                            onClick={() => setPage((p) => Math.min(totalPages, p + 1))}
                        >
                            Next
                        </button>
                    </div>
                </div>
                <table className="datatable">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        {pageUsers.map((u) => (
                            <tr
                                key={u.id}
                                className={Number(selectedUserId) === u.id ? 'selected' : ''}
                                onClick={() => setSelectedUserId(String(u.id))}
                            >
                                <td>{u.name}</td>
                                <td>{u.email}</td>
                                <td>
                                    {Number(selectedUserId) === u.id ? 'Dipilih' : 'Klik pilih'}
                                </td>
                            </tr>
                        ))}
                        {pageUsers.length === 0 && (
                            <tr>
                                <td colSpan="3">User tidak ditemukan.</td>
                            </tr>
                        )}
                    </tbody>
                </table>
            </div>
            <div className="panel">
                {loading && <p>Memuat permissions...</p>}
                {!loading && selectedUserId && (
                    <table className="perm-table">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th>View</th>
                                <th>Create</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            {ALL_MENUS.map((item) => {
                                const perms = selectedPermissions[item.slug] || {};
                                return (
                                    <tr key={item.slug}>
                                        <td>
                                            <span className="with-icon">
                                                <span className="icon">{getIcon(item.slug)}</span>
                                                {item.label}
                                            </span>
                                        </td>
                                        <td>
                                            <input
                                                type="checkbox"
                                                checked={!!perms.can_view}
                                                onChange={(e) => updatePermission(item.slug, 'can_view', e.target.checked)}
                                            />
                                        </td>
                                        <td>
                                            <input
                                                type="checkbox"
                                                checked={!!perms.can_create}
                                                onChange={(e) => updatePermission(item.slug, 'can_create', e.target.checked)}
                                            />
                                        </td>
                                        <td>
                                            <input
                                                type="checkbox"
                                                checked={!!perms.can_edit}
                                                onChange={(e) => updatePermission(item.slug, 'can_edit', e.target.checked)}
                                            />
                                        </td>
                                        <td>
                                            <input
                                                type="checkbox"
                                                checked={!!perms.can_delete}
                                                onChange={(e) => updatePermission(item.slug, 'can_delete', e.target.checked)}
                                            />
                                        </td>
                                    </tr>
                                );
                            })}
                        </tbody>
                    </table>
                )}
                <div className="actions">
                    <button onClick={handleSave} disabled={!selectedUserId}>Simpan</button>
                </div>
            </div>
        </div>
    );
}
