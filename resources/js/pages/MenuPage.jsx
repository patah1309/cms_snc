import React, { useEffect, useState } from 'react';
import Swal from 'sweetalert2';
import { ICONS } from '../components/IconMap';
import { swalDefaults } from '../utils/swal';
import SunEditor from 'suneditor-react';
import 'suneditor/dist/css/suneditor.min.css';

export default function MenuPage({ authApi }) {
    const [menus, setMenus] = useState([]);
    const [menuForm, setMenuForm] = useState({
        title: '',
        slug: '',
        url: '',
        parent_id: '',
        sort_order: 0,
        is_visible: true,
        page_title: '',
        page_body: '',
    });
    const [slugTouched, setSlugTouched] = useState(false);
    const [urlTouched, setUrlTouched] = useState(false);
    const [pageImage, setPageImage] = useState(null);
    const [pagePreviewUrl, setPagePreviewUrl] = useState(null);
    const [editingMenuId, setEditingMenuId] = useState(null);

    const loadMenus = async () => {
        try {
            const res = await authApi.get('/menus');
            setMenus(res.data.menus || []);
        } catch {
            setMenus([]);
        }
    };

    useEffect(() => {
        loadMenus();
    }, [authApi]);

    const buildTree = (items, parentId = null, depth = 0) => {
        return items
            .filter((item) => item.parent_id === parentId)
            .sort((a, b) => (a.sort_order ?? 0) - (b.sort_order ?? 0))
            .flatMap((item) => [
                { ...item, depth },
                ...buildTree(items, item.id, depth + 1),
            ]);
    };

    const treeMenus = buildTree(menus);
    const editingMenu = editingMenuId ? menus.find((item) => item.id === editingMenuId) : null;
    const editingHasChildren = editingMenu ? menus.some((item) => item.parent_id === editingMenu.id) : false;

    const toSlug = (value) => {
        return value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    };

    const resolveParentSlug = (parentId) => {
        if (!parentId) return '';
        const parent = menus.find((item) => item.id === Number(parentId));
        return parent?.slug || '';
    };

    const buildMenuUrl = (slug, parentId) => {
        if (!slug) return '';
        const parentSlug = resolveParentSlug(parentId);
        if (parentSlug) {
            return `/${parentSlug}/${slug}`;
        }
        return `/${slug}`;
    };

    const openCreateMenu = () => {
        setEditingMenuId(null);
        setMenuForm({
            title: '',
            slug: '',
            url: '',
            parent_id: '',
            sort_order: 0,
            is_visible: true,
            page_title: '',
            page_body: '',
        });
        setSlugTouched(false);
        setUrlTouched(false);
        setPageImage(null);
        setPagePreviewUrl(null);
    };

    const openEditMenu = (menu) => {
        setEditingMenuId(menu.id);
        setMenuForm({
            title: menu.title || '',
            slug: menu.slug || '',
            url: menu.url || '',
            parent_id: menu.parent_id || '',
            sort_order: menu.sort_order ?? 0,
            is_visible: !!menu.is_visible,
            page_title: menu.page?.title || '',
            page_body: menu.page?.body || '',
        });
        setSlugTouched(true);
        setUrlTouched(true);
        setPageImage(null);
        setPagePreviewUrl(menu.page?.image_path ? `/${menu.page.image_path}` : null);
    };

    useEffect(() => {
        if (!pageImage) {
            setPagePreviewUrl((prev) => (prev && prev.startsWith('blob:') ? null : prev));
            return;
        }
        const url = URL.createObjectURL(pageImage);
        setPagePreviewUrl(url);
        return () => URL.revokeObjectURL(url);
    }, [pageImage]);


    const handleMenuSubmit = async (e) => {
        e.preventDefault();
        const payload = new FormData();
        payload.append('title', menuForm.title);
        payload.append('slug', menuForm.slug || '');
        payload.append('url', menuForm.url || '');
        payload.append('parent_id', menuForm.parent_id ? String(menuForm.parent_id) : '');
        payload.append('sort_order', String(Number(menuForm.sort_order || 0)));
        payload.append('is_visible', menuForm.is_visible ? '1' : '0');
        payload.append('page_title', menuForm.page_title || '');
        payload.append('page_body', menuForm.page_body || '');
        if (pageImage) {
            payload.append('page_image', pageImage);
        }
        try {
            if (editingMenuId) {
                payload.append('_method', 'PUT');
                const res = await authApi.post(`/menus/${editingMenuId}`, payload, {
                    headers: { 'Content-Type': 'multipart/form-data' },
                });
                setMenus((prev) =>
                    prev.map((row) => (row.id === editingMenuId ? res.data.menu : row))
                );
            } else {
                const res = await authApi.post('/menus', payload, {
                    headers: { 'Content-Type': 'multipart/form-data' },
                });
                setMenus((prev) => [...prev, res.data.menu].sort((a, b) => a.sort_order - b.sort_order));
            }
            await loadMenus();
            const modalEl = document.getElementById('menuModal');
            if (window.bootstrap && modalEl) {
                window.bootstrap.Modal.getOrCreateInstance(modalEl).hide();
            }
        } catch (err) {
            const errorMessage = err?.response?.data?.message || 'Gagal menyimpan menu.';
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
                    <span className="icon">{ICONS.menus}</span>
                    Navigation Menu
                </h1>
                <p>Atur menu, submenu, urutan, dan tampil/sembunyi.</p>
            </div>
            <div className="panel">
                <div className="section-toolbar">
                    <h3>Daftar Menu</h3>
                    <button
                        className="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#menuModal"
                        onClick={openCreateMenu}
                    >
                        <i className="fa-solid fa-plus"></i> Tambah Menu
                    </button>
                </div>
                <div className="table-responsive">
                    <table className="table table-hover align-middle">
                        <thead className="table-light">
                            <tr>
                                <th>Judul</th>
                                <th>Slug</th>
                                <th>URL</th>
                                <th>Parent</th>
                                <th>Order</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {treeMenus.map((menu) => {
                                const parent = menus.find((item) => item.id === menu.parent_id);
                                return (
                                    <tr key={menu.id}>
                                        <td>
                                            <div className="menu-title" style={{ paddingLeft: `${menu.depth * 18}px` }}>
                                                {menu.depth > 0 && <span className="menu-branch">└</span>}
                                                <strong>{menu.title}</strong>
                                            </div>
                                        </td>
                                        <td className="muted">{menu.slug || '-'}</td>
                                        <td className="muted">{menu.url || '-'}</td>
                                        <td className="muted">{parent ? parent.title : '-'}</td>
                                        <td>{menu.sort_order}</td>
                                        <td>
                                            <span className={`badge ${menu.is_visible ? 'text-bg-success' : 'text-bg-secondary'}`}>
                                                {menu.is_visible ? 'Show' : 'Hide'}
                                            </span>
                                        </td>
                                        <td>
                                            <div className="btn-group">
                                                <button
                                                    className="btn btn-outline-secondary btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#menuModal"
                                                    onClick={() => openEditMenu(menu)}
                                                >
                                                    <i className="fa-regular fa-pen-to-square"></i>
                                                </button>
                                                <button
                                                    className="btn btn-outline-danger btn-sm"
                                                    onClick={async () => {
                                                        try {
                                                            await authApi.delete(`/menus/${menu.id}`);
                                                            await loadMenus();
                                                        } catch (err) {
                                                            const errorMessage = err?.response?.data?.message || 'Gagal menghapus menu.';
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
                                );
                            })}
                            {treeMenus.length === 0 && (
                                <tr>
                                    <td colSpan="7" className="text-center text-muted">Belum ada menu.</td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
            <div className="modal fade" id="menuModal" tabIndex="-1" aria-hidden="true" data-bs-focus="false">
                <div className="modal-dialog modal-lg">
                    <div className="modal-content">
                        <form onSubmit={handleMenuSubmit}>
                            <div className="modal-header">
                                <h5 className="modal-title">{editingMenuId ? 'Edit Menu' : 'Tambah Menu'}</h5>
                                <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div className="modal-body">
                                <div className="row g-3">
                                    <div className="col-md-6">
                                        <label className="form-label">Judul</label>
                                        <input
                                            className="form-control"
                                            value={menuForm.title}
                                            onChange={(e) => {
                                                const value = e.target.value;
                                                setMenuForm((prev) => {
                                                    const next = { ...prev, title: value };
                                                    if (!slugTouched) {
                                                        const nextSlug = toSlug(value);
                                                        next.slug = nextSlug;
                                                        if (!urlTouched) {
                                                            next.url = buildMenuUrl(nextSlug, prev.parent_id);
                                                        }
                                                    }
                                                    return next;
                                                });
                                            }}
                                            required
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label className="form-label">Slug</label>
                                        <input
                                            className="form-control"
                                            value={menuForm.slug}
                                            onChange={(e) => {
                                                const nextSlug = toSlug(e.target.value);
                                                setSlugTouched(true);
                                                setMenuForm((prev) => {
                                                    const next = { ...prev, slug: nextSlug };
                                                    if (!urlTouched) {
                                                        next.url = buildMenuUrl(nextSlug, prev.parent_id);
                                                    }
                                                    return next;
                                                });
                                            }}
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label className="form-label">URL</label>
                                        <input
                                            className="form-control"
                                            value={menuForm.url}
                                            onChange={(e) => {
                                                setUrlTouched(true);
                                                setMenuForm((prev) => ({ ...prev, url: e.target.value }));
                                            }}
                                            placeholder="https:// atau /about"
                                            disabled={editingHasChildren}
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label className="form-label">Parent</label>
                                        <select
                                            className="form-select"
                                            value={menuForm.parent_id}
                                            onChange={(e) => {
                                                const nextParentId = e.target.value;
                                                setMenuForm((prev) => {
                                                    const next = { ...prev, parent_id: nextParentId };
                                                    if (!urlTouched) {
                                                        next.url = buildMenuUrl(prev.slug, nextParentId);
                                                    }
                                                    return next;
                                                });
                                            }}
                                        >
                                            <option value="">Tidak ada</option>
                                            {menus.filter((m) => m.id !== editingMenuId).map((m) => (
                                                <option key={m.id} value={m.id}>{m.title}</option>
                                            ))}
                                        </select>
                                    </div>
                                    <div className="col-md-6">
                                        <label className="form-label">Order</label>
                                        <input
                                            className="form-control"
                                            type="number"
                                            min="0"
                                            value={menuForm.sort_order}
                                            onChange={(e) => setMenuForm((prev) => ({ ...prev, sort_order: e.target.value }))}
                                        />
                                    </div>
                                    <div className="col-md-6 d-flex align-items-center">
                                        <div className="form-check form-switch mt-4">
                                            <input
                                                className="form-check-input"
                                                type="checkbox"
                                                checked={menuForm.is_visible}
                                                onChange={(e) => setMenuForm((prev) => ({ ...prev, is_visible: e.target.checked }))}
                                            />
                                            <label className="form-check-label">Tampilkan</label>
                                        </div>
                                    </div>
                                    <div className="col-12">
                                        <hr />
                                        <h6 className="mb-3">Konten Halaman</h6>
                                        {editingHasChildren && (
                                            <div className="alert alert-warning mb-3">
                                                Menu parent tidak punya halaman.
                                            </div>
                                        )}
                                    </div>
                                    <div className="col-md-6">
                                        <label className="form-label">Judul Halaman</label>
                                        <input
                                            className="form-control"
                                            value={menuForm.page_title}
                                            onChange={(e) => setMenuForm((prev) => ({ ...prev, page_title: e.target.value }))}
                                            disabled={editingHasChildren}
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label className="form-label">Gambar</label>
                                        <input
                                            className="form-control"
                                            type="file"
                                            accept="image/*"
                                            onChange={(e) => setPageImage(e.target.files?.[0] || null)}
                                            disabled={editingHasChildren}
                                        />
                                        {pagePreviewUrl && (
                                            <div className="preview-wrap mt-2">
                                                <img src={pagePreviewUrl} alt="Preview" className="preview-thumb" />
                                            </div>
                                        )}
                                    </div>
                                    <div className="col-12">
                                        <label className="form-label">Penjelasan</label>
                                        <SunEditor
                                            setContents={menuForm.page_body}
                                            onChange={(value) => setMenuForm((prev) => ({ ...prev, page_body: value }))}
                                            height="260px"
                                            disable={editingHasChildren}
                                            setOptions={{
                                                imageUploadUrl: '/api/uploads/editor',
                                                imageUploadHeader: (() => {
                                                    const token = localStorage.getItem('auth_token');
                                                    return token ? { Authorization: `Bearer ${token}` } : {};
                                                })(),
                                                buttonList: [
                                                    ['undo', 'redo'],
                                                    ['formatBlock', 'bold', 'underline', 'italic', 'strike'],
                                                    ['fontColor', 'hiliteColor', 'removeFormat'],
                                                    ['align', 'list', 'outdent', 'indent'],
                                                    ['table', 'link', 'image', 'video'],
                                                    ['fullScreen', 'codeView', 'preview'],
                                                ],
                                            }}
                                        />
                                    </div>
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
