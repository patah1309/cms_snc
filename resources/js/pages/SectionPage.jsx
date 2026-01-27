import React, { useEffect, useRef, useState } from 'react';
import { useParams } from 'react-router-dom';
import Swal from 'sweetalert2';
import Quill from 'quill';
import SunEditor from 'suneditor-react';
import 'suneditor/dist/css/suneditor.min.css';
import { CONTENT_MENUS } from '../constants/menus';
import { getIcon } from '../components/IconMap';
import { swalDefaults } from '../utils/swal';

const TOGGLE_VISIBILITY_SLUGS = new Set(['home', 'services', 'team']);

export default function SectionPage({ permissions, sections, onToggleVisibility, authApi }) {
    const { slug } = useParams();
    const section = sections[slug];
    const menu = CONTENT_MENUS.find((item) => item.slug === slug);
    const [contents, setContents] = useState([]);
    const [loading, setLoading] = useState(false);
    const [contentForm, setContentForm] = useState({ title: '', body: '' });
    const [contentModalMode, setContentModalMode] = useState('create');
    const [contentModalId, setContentModalId] = useState(null);
    const [carouselSlides, setCarouselSlides] = useState([]);
    const [carouselForm, setCarouselForm] = useState({
        title: '',
        description: '',
        buttons: [{ label: '', url: '' }],
        sort_order: 0,
        is_active: true,
    });
    const [carouselImage, setCarouselImage] = useState(null);
    const [carouselPreviewUrl, setCarouselPreviewUrl] = useState(null);
    const [carouselEditingId, setCarouselEditingId] = useState(null);
    const [carouselEditingForm, setCarouselEditingForm] = useState({});
    const [carouselEditingImage, setCarouselEditingImage] = useState(null);
    const [carouselEditingPreviewUrl, setCarouselEditingPreviewUrl] = useState(null);
    const [previewImage, setPreviewImage] = useState(null);
    const [newsPosts, setNewsPosts] = useState([]);
    const [newsForm, setNewsForm] = useState({
        title: '',
        slug: '',
        category: '',
        summary: '',
        body: '',
        status: 'draft',
        published_at: '',
    });
    const summaryEditorRef = useRef(null);
    const bodyEditorRef = useRef(null);
    const summaryQuillRef = useRef(null);
    const bodyQuillRef = useRef(null);
    const [newsImage, setNewsImage] = useState(null);
    const [newsPreviewUrl, setNewsPreviewUrl] = useState(null);
    const [editingNewsId, setEditingNewsId] = useState(null);
    const [newsSlugTouched, setNewsSlugTouched] = useState(false);
    const [serviceItems, setServiceItems] = useState([]);
    const [serviceForm, setServiceForm] = useState({
        title: '',
        description: '',
        sort_order: 0,
        is_active: true,
    });
    const [serviceImage, setServiceImage] = useState(null);
    const [servicePreviewUrl, setServicePreviewUrl] = useState(null);
    const [editingServiceId, setEditingServiceId] = useState(null);
    const [teamMembers, setTeamMembers] = useState([]);
    const [teamForm, setTeamForm] = useState({
        name: '',
        position: '',
        description: '',
        sort_order: 0,
        is_active: true,
    });
    const [teamImage, setTeamImage] = useState(null);
    const [teamPreviewUrl, setTeamPreviewUrl] = useState(null);
    const [editingTeamId, setEditingTeamId] = useState(null);

    if (!menu) {
        return (
            <div className="content">
                <div className="page-header">
                    <h1>Menu tidak ditemukan</h1>
                </div>
            </div>
        );
    }

    const perms = permissions[slug] || {};

    useEffect(() => {
        if (!perms.can_view) {
            return;
        }
        setLoading(true);
        authApi.get(`/contents?menu=${slug}`)
            .then((res) => setContents(res.data.contents || []))
            .finally(() => setLoading(false));
    }, [authApi, perms.can_view, slug]);

    useEffect(() => {
        if (!perms.can_view || slug !== 'home') {
            return;
        }
        authApi.get('/home/carousels')
            .then((res) => setCarouselSlides(res.data.slides || []));
    }, [authApi, perms.can_view, slug]);

    useEffect(() => {
        if (!perms.can_view || slug !== 'news') {
            return;
        }
        authApi.get('/news')
            .then((res) => setNewsPosts(res.data.posts || []));
    }, [authApi, perms.can_view, slug]);

    useEffect(() => {
        if (!perms.can_view || slug !== 'services') {
            return;
        }
        authApi.get('/services')
            .then((res) => setServiceItems(res.data.items || []));
    }, [authApi, perms.can_view, slug]);

    useEffect(() => {
        if (!perms.can_view || slug !== 'team') {
            return;
        }
        authApi.get('/team')
            .then((res) => setTeamMembers(res.data.members || []));
    }, [authApi, perms.can_view, slug]);

    useEffect(() => {
        if (slug !== 'news') return;
        if (summaryEditorRef.current && !summaryQuillRef.current) {
            summaryQuillRef.current = new Quill(summaryEditorRef.current, {
                theme: 'snow',
                placeholder: 'Ringkasan...',
            });
            summaryQuillRef.current.on('text-change', () => {
                const html = summaryQuillRef.current.root.innerHTML;
                setNewsForm((prev) => ({ ...prev, summary: html }));
            });
        }
        if (bodyEditorRef.current && !bodyQuillRef.current) {
            bodyQuillRef.current = new Quill(bodyEditorRef.current, {
                theme: 'snow',
                placeholder: 'Isi berita...',
            });
            bodyQuillRef.current.on('text-change', () => {
                const html = bodyQuillRef.current.root.innerHTML;
                setNewsForm((prev) => ({ ...prev, body: html }));
            });
        }
    }, [slug]);

    useEffect(() => {
        if (slug !== 'services') return;
        if (serviceForm.description && serviceForm.description !== '') {
            return;
        }
    }, [slug, serviceForm.description]);

    useEffect(() => {
        if (slug !== 'news') return;
        if (summaryQuillRef.current) {
            const current = summaryQuillRef.current.root.innerHTML;
            if (current !== (newsForm.summary || '')) {
                summaryQuillRef.current.root.innerHTML = newsForm.summary || '';
            }
        }
        if (bodyQuillRef.current) {
            const current = bodyQuillRef.current.root.innerHTML;
            if (current !== (newsForm.body || '')) {
                bodyQuillRef.current.root.innerHTML = newsForm.body || '';
            }
        }
    }, [newsForm.summary, newsForm.body, slug]);

    useEffect(() => {
        if (!carouselImage) {
            setCarouselPreviewUrl(null);
            return;
        }
        const url = URL.createObjectURL(carouselImage);
        setCarouselPreviewUrl(url);
        return () => URL.revokeObjectURL(url);
    }, [carouselImage]);

    useEffect(() => {
        if (!carouselEditingImage) {
            return;
        }
        const url = URL.createObjectURL(carouselEditingImage);
        setCarouselEditingPreviewUrl(url);
        return () => URL.revokeObjectURL(url);
    }, [carouselEditingImage]);

    useEffect(() => {
        if (!newsImage) {
            setNewsPreviewUrl(null);
            return;
        }
        const url = URL.createObjectURL(newsImage);
        setNewsPreviewUrl(url);
        return () => URL.revokeObjectURL(url);
    }, [newsImage]);

    useEffect(() => {
        if (!serviceImage) {
            setServicePreviewUrl(null);
            return;
        }
        const url = URL.createObjectURL(serviceImage);
        setServicePreviewUrl(url);
        return () => URL.revokeObjectURL(url);
    }, [serviceImage]);

    useEffect(() => {
        if (!teamImage) {
            setTeamPreviewUrl(null);
            return;
        }
        const url = URL.createObjectURL(teamImage);
        setTeamPreviewUrl(url);
        return () => URL.revokeObjectURL(url);
    }, [teamImage]);

    const resetCarouselForm = () => {
        setCarouselForm({
            title: '',
            description: '',
            buttons: [{ label: '', url: '' }],
            sort_order: 0,
            is_active: true,
        });
        setCarouselImage(null);
        setCarouselPreviewUrl(null);
    };

    const resetNewsForm = () => {
        setNewsForm({
            title: '',
            slug: '',
            category: '',
            summary: '',
            body: '',
            status: 'draft',
            published_at: '',
        });
        setNewsImage(null);
        setNewsPreviewUrl(null);
        setEditingNewsId(null);
        setNewsSlugTouched(false);
    };

    const resetServiceForm = () => {
        setServiceForm({
            title: '',
            description: '',
            sort_order: 0,
            is_active: true,
        });
        setServiceImage(null);
        setServicePreviewUrl(null);
        setEditingServiceId(null);
    };

    const resetTeamForm = () => {
        setTeamForm({
            name: '',
            position: '',
            description: '',
            sort_order: 0,
            is_active: true,
        });
        setTeamImage(null);
        setTeamPreviewUrl(null);
        setEditingTeamId(null);
    };

    const normalizeCarouselButtons = (buttons, fallbackLabel, fallbackUrl) => {
        if (Array.isArray(buttons) && buttons.length > 0) {
            return buttons.map((button) => ({
                label: button?.label || '',
                url: button?.url || '',
            }));
        }
        if (fallbackLabel || fallbackUrl) {
            return [{ label: fallbackLabel || '', url: fallbackUrl || '' }];
        }
        return [{ label: '', url: '' }];
    };

    const appendCarouselButtons = (payload, buttons) => {
        if (!Array.isArray(buttons)) return;
        buttons
            .filter((button) => button.label || button.url)
            .forEach((button, index) => {
                payload.append(`buttons[${index}][label]`, button.label || '');
                payload.append(`buttons[${index}][url]`, button.url || '');
            });
    };

    const handleCarouselSubmit = async (e) => {
        e.preventDefault();
        const payload = new FormData();
        payload.append('title', carouselForm.title || '');
        payload.append('description', carouselForm.description || '');
        payload.append('sort_order', String(carouselForm.sort_order ?? 0));
        payload.append('is_active', carouselForm.is_active ? '1' : '0');
        appendCarouselButtons(payload, carouselForm.buttons);
        if (carouselImage) {
            payload.append('image', carouselImage);
        }
        try {
            const res = await authApi.post('/home/carousels', payload, {
                headers: { 'Content-Type': 'multipart/form-data' },
            });
            setCarouselSlides((prev) => [...prev, res.data.slide].sort((a, b) => a.sort_order - b.sort_order));
            resetCarouselForm();
            const modalEl = document.getElementById('carouselModal');
            if (window.bootstrap && modalEl) {
                window.bootstrap.Modal.getOrCreateInstance(modalEl).hide();
            }
        } catch (err) {
            const errorMessage = err?.response?.data?.message || 'Gagal menambah slide.';
            await Swal.fire({
                ...swalDefaults,
                icon: 'error',
                title: 'Gagal',
                text: errorMessage,
                confirmButtonText: 'OK',
            });
        }
    };

    const startCarouselEdit = (slide) => {
        setCarouselEditingId(slide.id);
        setCarouselEditingForm({
            title: slide.title || '',
            description: slide.description || '',
            buttons: normalizeCarouselButtons(slide.buttons, slide.button_label, slide.button_url),
            sort_order: slide.sort_order ?? 0,
            is_active: !!slide.is_active,
        });
        setCarouselEditingImage(null);
        setCarouselEditingPreviewUrl(slide.image_url || null);
    };

    const handleCarouselUpdate = async (e) => {
        e.preventDefault();
        if (!carouselEditingId) return;
        const payload = new FormData();
        payload.append('title', carouselEditingForm.title || '');
        payload.append('description', carouselEditingForm.description || '');
        payload.append('sort_order', String(carouselEditingForm.sort_order ?? 0));
        payload.append('is_active', carouselEditingForm.is_active ? '1' : '0');
        appendCarouselButtons(payload, carouselEditingForm.buttons);
        if (carouselEditingImage) {
            payload.append('image', carouselEditingImage);
        }
        payload.append('_method', 'PUT');
        try {
            const res = await authApi.post(`/home/carousels/${carouselEditingId}`, payload, {
                headers: { 'Content-Type': 'multipart/form-data' },
            });
            setCarouselSlides((prev) =>
                prev.map((slide) => (slide.id === carouselEditingId ? res.data.slide : slide))
                    .sort((a, b) => a.sort_order - b.sort_order)
            );
            setCarouselEditingId(null);
            setCarouselEditingForm({});
            setCarouselEditingImage(null);
            setCarouselEditingPreviewUrl(null);
            const modalEl = document.getElementById('carouselModal');
            if (window.bootstrap && modalEl) {
                window.bootstrap.Modal.getOrCreateInstance(modalEl).hide();
            }
        } catch (err) {
            const errorMessage = err?.response?.data?.message || 'Gagal memperbarui slide.';
            await Swal.fire({
                ...swalDefaults,
                icon: 'error',
                title: 'Gagal',
                text: errorMessage,
                confirmButtonText: 'OK',
            });
        }
    };

    const openCreateContent = () => {
        setContentModalMode('create');
        setContentModalId(null);
        setContentForm({ title: '', body: '' });
    };

    const openEditContent = (item) => {
        setContentModalMode('edit');
        setContentModalId(item.id);
        setContentForm({ title: item.title || '', body: item.body || '' });
    };

    const handleContentSubmit = async (e) => {
        e.preventDefault();
        try {
            if (contentModalMode === 'create') {
                const res = await authApi.post('/contents', {
                    menu: slug,
                    title: contentForm.title,
                    body: contentForm.body,
                });
                setContents((prev) => [res.data.content, ...prev]);
            } else if (contentModalId) {
                const res = await authApi.put(`/contents/${contentModalId}`, {
                    title: contentForm.title,
                    body: contentForm.body,
                });
                setContents((prev) =>
                    prev.map((row) => (row.id === contentModalId ? res.data.content : row))
                );
            }
            const modalEl = document.getElementById('contentModal');
            if (window.bootstrap && modalEl) {
                window.bootstrap.Modal.getOrCreateInstance(modalEl).hide();
            }
        } catch (err) {
            const errorMessage = err?.response?.data?.message || 'Gagal menyimpan konten.';
            await Swal.fire({
                ...swalDefaults,
                icon: 'error',
                title: 'Gagal',
                text: errorMessage,
                confirmButtonText: 'OK',
            });
        }
    };

    const openCreateNews = () => {
        resetNewsForm();
    };

    const openEditNews = (post) => {
        setEditingNewsId(post.id);
        setNewsSlugTouched(true);
        const publishedAt = post.published_at
            ? post.published_at.replace(' ', 'T').slice(0, 16)
            : '';
        setNewsForm({
            title: post.title || '',
            slug: post.slug || '',
            category: post.category || '',
            summary: post.summary || '',
            body: post.body || '',
            status: post.status || 'draft',
            published_at: publishedAt,
        });
        setNewsImage(null);
        setNewsPreviewUrl(post.cover_url || null);
    };

    const toSlug = (value) => {
        return value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    };

    const handleNewsSubmit = async (e) => {
        e.preventDefault();
        const payload = new FormData();
        Object.entries(newsForm).forEach(([key, value]) => {
            if (key === 'published_at' && !value) {
                return;
            }
            payload.append(key, value);
        });
        if (newsImage) {
            payload.append('cover_image', newsImage);
        }
        if (editingNewsId) {
            payload.append('_method', 'PUT');
        }
        try {
            const url = editingNewsId ? `/news/${editingNewsId}` : '/news';
            const res = await authApi.post(url, payload, {
                headers: { 'Content-Type': 'multipart/form-data' },
            });
            if (editingNewsId) {
                setNewsPosts((prev) =>
                    prev.map((row) => (row.id === editingNewsId ? res.data.post : row))
                );
            } else {
                setNewsPosts((prev) => [res.data.post, ...prev]);
            }
            const modalEl = document.getElementById('newsModal');
            if (window.bootstrap && modalEl) {
                window.bootstrap.Modal.getOrCreateInstance(modalEl).hide();
            }
            resetNewsForm();
        } catch (err) {
            const errorMessage = err?.response?.data?.message || 'Gagal menyimpan news.';
            await Swal.fire({
                ...swalDefaults,
                icon: 'error',
                title: 'Gagal',
                text: errorMessage,
                confirmButtonText: 'OK',
            });
        }
    };

    const openCreateService = () => {
        resetServiceForm();
    };

    const openEditService = (item) => {
        setEditingServiceId(item.id);
        setServiceForm({
            title: item.title || '',
            description: item.description || '',
            sort_order: item.sort_order ?? 0,
            is_active: !!item.is_active,
        });
        setServiceImage(null);
        setServicePreviewUrl(item.cover_url || null);
    };

    const handleServiceSubmit = async (e) => {
        e.preventDefault();
        const payload = new FormData();
        Object.entries(serviceForm).forEach(([key, value]) => {
            if (key === 'is_active') {
                payload.append(key, value ? '1' : '0');
                return;
            }
            payload.append(key, value);
        });
        if (serviceImage) {
            payload.append('cover_image', serviceImage);
        }
        if (editingServiceId) {
            payload.append('_method', 'PUT');
        }
        try {
            const url = editingServiceId ? `/services/${editingServiceId}` : '/services';
            const res = await authApi.post(url, payload, {
                headers: { 'Content-Type': 'multipart/form-data' },
            });
            if (editingServiceId) {
                setServiceItems((prev) =>
                    prev.map((row) => (row.id === editingServiceId ? res.data.item : row))
                );
            } else {
                setServiceItems((prev) => [...prev, res.data.item].sort((a, b) => a.sort_order - b.sort_order));
            }
            const modalEl = document.getElementById('serviceModal');
            if (window.bootstrap && modalEl) {
                window.bootstrap.Modal.getOrCreateInstance(modalEl).hide();
            }
            resetServiceForm();
        } catch (err) {
            const errorMessage = err?.response?.data?.message || 'Gagal menyimpan service.';
            await Swal.fire({
                ...swalDefaults,
                icon: 'error',
                title: 'Gagal',
                text: errorMessage,
                confirmButtonText: 'OK',
            });
        }
    };

    const openCreateTeam = () => {
        resetTeamForm();
    };

    const openEditTeam = (member) => {
        setEditingTeamId(member.id);
        setTeamForm({
            name: member.name || '',
            position: member.position || '',
            description: member.description || '',
            sort_order: member.sort_order ?? 0,
            is_active: !!member.is_active,
        });
        setTeamImage(null);
        setTeamPreviewUrl(member.photo_url || null);
    };

    const handleTeamSubmit = async (e) => {
        e.preventDefault();
        const payload = new FormData();
        Object.entries(teamForm).forEach(([key, value]) => {
            if (key === 'is_active') {
                payload.append(key, value ? '1' : '0');
                return;
            }
            payload.append(key, value);
        });
        if (teamImage) {
            payload.append('photo', teamImage);
        }
        if (editingTeamId) {
            payload.append('_method', 'PUT');
        }
        try {
            const url = editingTeamId ? `/team/${editingTeamId}` : '/team';
            const res = await authApi.post(url, payload, {
                headers: { 'Content-Type': 'multipart/form-data' },
            });
            if (editingTeamId) {
                setTeamMembers((prev) =>
                    prev.map((row) => (row.id === editingTeamId ? res.data.member : row))
                );
            } else {
                setTeamMembers((prev) => [...prev, res.data.member].sort((a, b) => a.sort_order - b.sort_order));
            }
            const modalEl = document.getElementById('teamModal');
            if (window.bootstrap && modalEl) {
                window.bootstrap.Modal.getOrCreateInstance(modalEl).hide();
            }
            resetTeamForm();
        } catch (err) {
            const errorMessage = err?.response?.data?.message || 'Gagal menyimpan team.';
            await Swal.fire({
                ...swalDefaults,
                icon: 'error',
                title: 'Gagal',
                text: errorMessage,
                confirmButtonText: 'OK',
            });
        }
    };

    const decodeHtml = (value) => {
        if (!value) return '';
        const el = document.createElement('textarea');
        el.innerHTML = value;
        return el.value;
    };

    const stripHtml = (value) => {
        if (!value) return '';
        return decodeHtml(value.replace(/<[^>]*>/g, '')).trim();
    };

    const handlePublishNews = async (post) => {
        try {
            const payload = new FormData();
            payload.append('title', post.title);
            payload.append('slug', post.slug);
            payload.append('category', post.category || '');
            payload.append('summary', post.summary || '');
            payload.append('body', post.body || '');
            payload.append('status', 'published');
            payload.append('_method', 'PUT');
            const res = await authApi.post(`/news/${post.id}`, payload, {
                headers: { 'Content-Type': 'multipart/form-data' },
            });
            setNewsPosts((prev) =>
                prev.map((row) => (row.id === post.id ? res.data.post : row))
            );
        } catch (err) {
            const errorMessage = err?.response?.data?.message || 'Gagal publish news.';
            await Swal.fire({
                ...swalDefaults,
                icon: 'error',
                title: 'Gagal',
                text: errorMessage,
                confirmButtonText: 'OK',
            });
        }
    };

    if (!perms.can_view) {
        return (
            <div className="content">
                <div className="page-header">
                    <h1>{menu.label}</h1>
                    <p>Anda tidak memiliki akses ke menu ini.</p>
                </div>
            </div>
        );
    }

    return (
        <div className="content">
            <div className="page-header">
                <h1 className="with-icon">
                    <span className="icon">{getIcon(menu.slug)}</span>
                    {menu.label}
                </h1>
                <p>Kelola konten {menu.label.toLowerCase()}.</p>
            </div>
            {TOGGLE_VISIBILITY_SLUGS.has(slug) && section && (
                <div className="panel">
                    <div className="toggle-row">
                        <div>
                            <h4>Tampilkan di website</h4>
                            <p>Gunakan untuk show/hide menu {menu.label}.</p>
                        </div>
                        <label className="switch-toggle">
                            <input
                                type="checkbox"
                                checked={section.is_visible}
                                onChange={() => onToggleVisibility(slug)}
                            />
                            <span className="slider" />
                        </label>
                    </div>
                </div>
            )}
            {slug === 'home' && (
                <div className="panel">
                    <div className="section-toolbar">
                        <h3>Carousel Home</h3>
                        {perms.can_create && (
                            <button
                                className="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#carouselModal"
                                onClick={() => {
                                    setCarouselEditingId(null);
                                    setCarouselEditingForm({});
                                    setCarouselEditingImage(null);
                                    resetCarouselForm();
                                }}
                            >
                                <i className="fa-solid fa-plus"></i> Tambah Slide
                            </button>
                        )}
                    </div>
                    <div className="table-responsive">
                        <table className="table table-hover align-middle">
                            <thead className="table-light">
                                <tr>
                                    <th>Preview</th>
                                    <th>Judul</th>
                                    <th>Deskripsi</th>
                                    <th>Order</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {carouselSlides.map((slide) => (
                                    <tr key={slide.id}>
                                        <td>
                                            {slide.image_url ? (
                                                <button
                                                    type="button"
                                                    className="thumb-button"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#imagePreviewModal"
                                                    onClick={() => setPreviewImage(slide.image_url)}
                                                >
                                                    <img src={slide.image_url} alt={slide.title || 'Slide'} className="thumb" />
                                                </button>
                                            ) : (
                                                <span className="helper">No image</span>
                                            )}
                                        </td>
                                        <td><strong>{slide.title || 'Tanpa judul'}</strong></td>
                                        <td className="muted">{stripHtml(slide.description).slice(0, 80)}</td>
                                        <td>{slide.sort_order}</td>
                                        <td>
                                            <span className={`badge ${slide.is_active ? 'text-bg-success' : 'text-bg-secondary'}`}>
                                                {slide.is_active ? 'Aktif' : 'Nonaktif'}
                                            </span>
                                        </td>
                                        <td>
                                            <div className="btn-group">
                                                <button
                                                    className="btn btn-outline-secondary btn-sm"
                                                    disabled={!perms.can_edit}
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#carouselModal"
                                                    onClick={() => startCarouselEdit(slide)}
                                                >
                                                    <i className="fa-regular fa-pen-to-square"></i>
                                                </button>
                                                <button
                                                    className="btn btn-outline-danger btn-sm"
                                                    disabled={!perms.can_delete}
                                                    onClick={async () => {
                                                        try {
                                                            await authApi.delete(`/home/carousels/${slide.id}`);
                                                            setCarouselSlides((prev) => prev.filter((row) => row.id !== slide.id));
                                                        } catch (err) {
                                                            const errorMessage = err?.response?.data?.message || 'Gagal menghapus slide.';
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
                                {carouselSlides.length === 0 && (
                                    <tr>
                                        <td colSpan="6" className="text-center text-muted">Belum ada slide.</td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            )}
            {slug === 'services' && (
                <div className="panel">
                    <div className="section-toolbar">
                        <h3>Services</h3>
                        {perms.can_create && (
                            <button
                                className="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#serviceModal"
                                onClick={openCreateService}
                            >
                                <i className="fa-solid fa-plus"></i> Tambah Service
                            </button>
                        )}
                    </div>
                    <div className="table-responsive">
                        <table className="table table-hover align-middle">
                            <thead className="table-light">
                                <tr>
                                    <th>Cover</th>
                                    <th>Service</th>
                                    <th>Deskripsi</th>
                                    <th>Order</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {serviceItems.map((item) => (
                                    <tr key={item.id}>
                                        <td>
                                            {item.cover_url ? (
                                                <button
                                                    type="button"
                                                    className="thumb-button"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#imagePreviewModal"
                                                    onClick={() => setPreviewImage(item.cover_url)}
                                                >
                                                    <img src={item.cover_url} alt={item.title} className="thumb" />
                                                </button>
                                            ) : (
                                                <span className="helper">No image</span>
                                            )}
                                        </td>
                                        <td><strong>{item.title}</strong></td>
                                        <td className="muted">{stripHtml(item.description).slice(0, 120)}</td>
                                        <td>{item.sort_order}</td>
                                        <td>
                                            <span className={`badge ${item.is_active ? 'text-bg-success' : 'text-bg-secondary'}`}>
                                                {item.is_active ? 'Aktif' : 'Nonaktif'}
                                            </span>
                                        </td>
                                        <td>
                                            <div className="btn-group">
                                                <button
                                                    className="btn btn-outline-secondary btn-sm"
                                                    disabled={!perms.can_edit}
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#serviceModal"
                                                    onClick={() => openEditService(item)}
                                                >
                                                    <i className="fa-regular fa-pen-to-square"></i>
                                                </button>
                                                <button
                                                    className="btn btn-outline-danger btn-sm"
                                                    disabled={!perms.can_delete}
                                                    onClick={async () => {
                                                        try {
                                                            await authApi.delete(`/services/${item.id}`);
                                                            setServiceItems((prev) => prev.filter((row) => row.id !== item.id));
                                                        } catch (err) {
                                                            const errorMessage = err?.response?.data?.message || 'Gagal menghapus service.';
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
                                {serviceItems.length === 0 && (
                                    <tr>
                                        <td colSpan="6" className="text-center text-muted">Belum ada service.</td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            )}
            {slug === 'team' && (
                <div className="panel">
                    <div className="section-toolbar">
                        <h3>Team</h3>
                        {perms.can_create && (
                            <button
                                className="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#teamModal"
                                onClick={openCreateTeam}
                            >
                                <i className="fa-solid fa-plus"></i> Tambah Team
                            </button>
                        )}
                    </div>
                    <div className="table-responsive">
                        <table className="table table-hover align-middle">
                            <thead className="table-light">
                                <tr>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Description</th>
                                    <th>Order</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {teamMembers.map((member) => (
                                    <tr key={member.id}>
                                        <td>
                                            {member.photo_url ? (
                                                <button
                                                    type="button"
                                                    className="thumb-button"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#imagePreviewModal"
                                                    onClick={() => setPreviewImage(member.photo_url)}
                                                >
                                                    <img src={member.photo_url} alt={member.name || 'Team'} className="thumb" />
                                                </button>
                                            ) : (
                                                <span className="helper">No image</span>
                                            )}
                                        </td>
                                        <td><strong>{member.name}</strong></td>
                                        <td className="muted">{member.position || '-'}</td>
                                        <td className="muted">{member.description || '-'}</td>
                                        <td>{member.sort_order}</td>
                                        <td>
                                            <span className={`badge ${member.is_active ? 'text-bg-success' : 'text-bg-secondary'}`}>
                                                {member.is_active ? 'Show' : 'Hide'}
                                            </span>
                                        </td>
                                        <td>
                                            <div className="btn-group">
                                                <button
                                                    className="btn btn-outline-secondary btn-sm"
                                                    disabled={!perms.can_edit}
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#teamModal"
                                                    onClick={() => openEditTeam(member)}
                                                >
                                                    <i className="fa-regular fa-pen-to-square"></i>
                                                </button>
                                                <button
                                                    className="btn btn-outline-danger btn-sm"
                                                    disabled={!perms.can_delete}
                                                    onClick={async () => {
                                                        try {
                                                            await authApi.delete(`/team/${member.id}`);
                                                            setTeamMembers((prev) => prev.filter((row) => row.id !== member.id));
                                                        } catch (err) {
                                                            const errorMessage = err?.response?.data?.message || 'Gagal menghapus team.';
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
                                    {teamMembers.length === 0 && (
                                        <tr>
                                            <td colSpan="7" className="text-center text-muted">No team members yet.</td>
                                        </tr>
                                    )}
                            </tbody>
                        </table>
                    </div>
                </div>
            )}
            {slug === 'news' && (
                <div className="panel">
                    <div className="section-toolbar">
                        <h3>News</h3>
                        {perms.can_create && (
                            <button
                                className="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#newsModal"
                                onClick={openCreateNews}
                            >
                                <i className="fa-solid fa-plus"></i> Tambah News
                            </button>
                        )}
                    </div>
                    <div className="table-responsive">
                        <table className="table table-hover align-middle">
                            <thead className="table-light">
                                <tr>
                                    <th>Cover</th>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Ringkasan</th>
                                    <th>Isi</th>
                                    <th>Status</th>
                                    <th>Publish</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {newsPosts.map((post) => (
                                    <tr key={post.id}>
                                        <td>
                                            {post.cover_url ? (
                                                <button
                                                    type="button"
                                                    className="thumb-button"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#imagePreviewModal"
                                                    onClick={() => setPreviewImage(post.cover_url)}
                                                >
                                                    <img src={post.cover_url} alt={post.title} className="thumb" />
                                                </button>
                                            ) : (
                                                <span className="helper">No image</span>
                                            )}
                                        </td>
                                        <td><strong>{post.title}</strong></td>
                                        <td className="muted">{post.category || '-'}</td>
                                        <td className="muted">{stripHtml(post.summary).slice(0, 120)}</td>
                                        <td className="muted">{stripHtml(post.body).slice(0, 120)}</td>
                                        <td>
                                            <span className={`badge ${post.status === 'published' ? 'text-bg-success' : 'text-bg-secondary'}`}>
                                                {post.status}
                                            </span>
                                        </td>
                                        <td className="muted">{post.published_at?.slice(0, 10) || '-'}</td>
                                        <td>
                                            <div className="btn-group">
                                                <button
                                                    className="btn btn-outline-secondary btn-sm"
                                                    disabled={!perms.can_edit}
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#newsModal"
                                                    onClick={() => openEditNews(post)}
                                                >
                                                    <i className="fa-regular fa-pen-to-square"></i>
                                                </button>
                                                <button
                                                    className="btn btn-outline-success btn-sm"
                                                    disabled={!perms.can_edit || post.status === 'published'}
                                                    onClick={() => handlePublishNews(post)}
                                                    title="Publish"
                                                >
                                                    <i className="fa-solid fa-upload"></i>
                                                </button>
                                                <button
                                                    className="btn btn-outline-danger btn-sm"
                                                    disabled={!perms.can_delete}
                                                    onClick={async () => {
                                                        try {
                                                            await authApi.delete(`/news/${post.id}`);
                                                            setNewsPosts((prev) => prev.filter((row) => row.id !== post.id));
                                                        } catch (err) {
                                                            const errorMessage = err?.response?.data?.message || 'Gagal menghapus news.';
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
                                {newsPosts.length === 0 && (
                                    <tr>
                                        <td colSpan="8" className="text-center text-muted">Belum ada news.</td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            )}
            {slug !== 'home' && slug !== 'news' && slug !== 'services' && slug !== 'team' && (
                <div className="panel">
                    <div className="section-toolbar">
                        <h3>Konten</h3>
                        {perms.can_create && (
                            <button
                                className="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#contentModal"
                                onClick={openCreateContent}
                            >
                                <i className="fa-solid fa-plus"></i> Tambah Konten
                            </button>
                        )}
                    </div>
                    {loading && <p>Memuat konten...</p>}
                    {!loading && (
                        <div className="table-responsive">
                            <table className="table table-hover align-middle">
                                <thead className="table-light">
                                    <tr>
                                        <th>Judul</th>
                                        <th>Deskripsi</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {contents.map((item) => (
                                        <tr key={item.id}>
                                            <td><strong>{item.title}</strong></td>
                                            <td className="muted">{(item.body || '').slice(0, 80)}</td>
                                            <td>{item.created_at?.slice(0, 10) || '-'}</td>
                                            <td>
                                                <div className="btn-group">
                                                    <button
                                                        className="btn btn-outline-secondary btn-sm"
                                                        disabled={!perms.can_edit}
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#contentModal"
                                                        onClick={() => openEditContent(item)}
                                                    >
                                                        <i className="fa-regular fa-pen-to-square"></i>
                                                    </button>
                                                    <button
                                                        className="btn btn-outline-danger btn-sm"
                                                        disabled={!perms.can_delete}
                                                        onClick={async () => {
                                                            try {
                                                                await authApi.delete(`/contents/${item.id}`);
                                                                setContents((prev) => prev.filter((row) => row.id !== item.id));
                                                            } catch (err) {
                                                                const errorMessage = err?.response?.data?.message || 'Gagal menghapus konten.';
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
                                    {contents.length === 0 && (
                                        <tr>
                                            <td colSpan="4" className="text-center text-muted">Belum ada konten.</td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    )}
                </div>
            )}
            <div className="modal fade" id="contentModal" tabIndex="-1" aria-hidden="true">
                <div className="modal-dialog modal-lg">
                    <div className="modal-content">
                        <form onSubmit={handleContentSubmit}>
                            <div className="modal-header">
                                <h5 className="modal-title">
                                    {contentModalMode === 'create' ? 'Tambah Konten' : 'Edit Konten'}
                                </h5>
                                <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div className="modal-body">
                                <div className="mb-3">
                                    <label className="form-label">Judul</label>
                                    <input
                                        className="form-control"
                                        value={contentForm.title}
                                        onChange={(e) => setContentForm((prev) => ({ ...prev, title: e.target.value }))}
                                        required
                                    />
                                </div>
                                <div className="mb-3">
                                    <label className="form-label">Deskripsi</label>
                                    <textarea
                                        className="form-control"
                                        rows="4"
                                        value={contentForm.body}
                                        onChange={(e) => setContentForm((prev) => ({ ...prev, body: e.target.value }))}
                                    />
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
            <div className="modal fade" id="carouselModal" tabIndex="-1" aria-hidden="true">
                <div className="modal-dialog modal-lg">
                    <div className="modal-content">
                        <form onSubmit={carouselEditingId ? handleCarouselUpdate : handleCarouselSubmit}>
                            <div className="modal-header">
                                <h5 className="modal-title">
                                    {carouselEditingId ? 'Edit Slide' : 'Tambah Slide'}
                                </h5>
                                <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div className="modal-body">
                                <div className="row g-3">
                                    <div className="col-md-6">
                                        <label className="form-label">Judul</label>
                                        <input
                                            className="form-control"
                                            value={carouselEditingId ? (carouselEditingForm.title || '') : carouselForm.title}
                                            onChange={(e) => {
                                                const value = e.target.value;
                                                if (carouselEditingId) {
                                                    setCarouselEditingForm((prev) => ({ ...prev, title: value }));
                                                } else {
                                                    setCarouselForm((prev) => ({ ...prev, title: value }));
                                                }
                                            }}
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label className="form-label">Urutan</label>
                                        <input
                                            className="form-control"
                                            type="number"
                                            min="0"
                                            value={carouselEditingId ? (carouselEditingForm.sort_order ?? 0) : carouselForm.sort_order}
                                            onChange={(e) => {
                                                const value = Number(e.target.value);
                                                if (carouselEditingId) {
                                                    setCarouselEditingForm((prev) => ({ ...prev, sort_order: value }));
                                                } else {
                                                    setCarouselForm((prev) => ({ ...prev, sort_order: value }));
                                                }
                                            }}
                                        />
                                    </div>
                                    <div className="col-12">
                                        <label className="form-label">Deskripsi</label>
                                        <SunEditor
                                            key={carouselEditingId ? `carousel-desc-${carouselEditingId}` : 'carousel-desc-new'}
                                            setContents={carouselEditingId ? (carouselEditingForm.description || '') : carouselForm.description}
                                            onChange={(value) => {
                                                if (carouselEditingId) {
                                                    setCarouselEditingForm((prev) => ({ ...prev, description: value }));
                                                } else {
                                                    setCarouselForm((prev) => ({ ...prev, description: value }));
                                                }
                                            }}
                                            height="180px"
                                            setOptions={{
                                                buttonList: [
                                                    ['undo', 'redo'],
                                                    ['formatBlock', 'bold', 'underline', 'italic', 'strike'],
                                                    ['fontColor', 'hiliteColor', 'removeFormat'],
                                                    ['align', 'list', 'outdent', 'indent'],
                                                    ['table', 'link'],
                                                    ['fullScreen', 'codeView', 'preview'],
                                                ],
                                            }}
                                        />
                                    </div>
                                    <div className="col-12">
                                        <label className="form-label">Buttons</label>
                                        <div className="d-grid gap-2">
                                            {(carouselEditingId ? (carouselEditingForm.buttons || []) : carouselForm.buttons).map((button, index) => (
                                                <div className="row g-2" key={`carousel-button-${index}`}>
                                                    <div className="col-md-5">
                                                        <input
                                                            className="form-control"
                                                            placeholder="Label"
                                                            value={button.label || ''}
                                                            onChange={(e) => {
                                                                const value = e.target.value;
                                                                if (carouselEditingId) {
                                                                    setCarouselEditingForm((prev) => {
                                                                        const nextButtons = [...(prev.buttons || [])];
                                                                        nextButtons[index] = { ...nextButtons[index], label: value };
                                                                        return { ...prev, buttons: nextButtons };
                                                                    });
                                                                } else {
                                                                    setCarouselForm((prev) => {
                                                                        const nextButtons = [...(prev.buttons || [])];
                                                                        nextButtons[index] = { ...nextButtons[index], label: value };
                                                                        return { ...prev, buttons: nextButtons };
                                                                    });
                                                                }
                                                            }}
                                                        />
                                                    </div>
                                                    <div className="col-md-5">
                                                        <input
                                                            className="form-control"
                                                            placeholder="URL"
                                                            value={button.url || ''}
                                                            onChange={(e) => {
                                                                const value = e.target.value;
                                                                if (carouselEditingId) {
                                                                    setCarouselEditingForm((prev) => {
                                                                        const nextButtons = [...(prev.buttons || [])];
                                                                        nextButtons[index] = { ...nextButtons[index], url: value };
                                                                        return { ...prev, buttons: nextButtons };
                                                                    });
                                                                } else {
                                                                    setCarouselForm((prev) => {
                                                                        const nextButtons = [...(prev.buttons || [])];
                                                                        nextButtons[index] = { ...nextButtons[index], url: value };
                                                                        return { ...prev, buttons: nextButtons };
                                                                    });
                                                                }
                                                            }}
                                                        />
                                                    </div>
                                                    <div className="col-md-2 d-grid">
                                                        <button
                                                            type="button"
                                                            className="btn btn-outline-danger"
                                                            onClick={() => {
                                                                if (carouselEditingId) {
                                                                    setCarouselEditingForm((prev) => {
                                                                        const nextButtons = (prev.buttons || []).filter((_, idx) => idx !== index);
                                                                        return { ...prev, buttons: nextButtons.length ? nextButtons : [{ label: '', url: '' }] };
                                                                    });
                                                                } else {
                                                                    setCarouselForm((prev) => {
                                                                        const nextButtons = (prev.buttons || []).filter((_, idx) => idx !== index);
                                                                        return { ...prev, buttons: nextButtons.length ? nextButtons : [{ label: '', url: '' }] };
                                                                    });
                                                                }
                                                            }}
                                                        >
                                                            Hapus
                                                        </button>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                        <button
                                            type="button"
                                            className="btn btn-outline-secondary btn-sm mt-2"
                                            onClick={() => {
                                                if (carouselEditingId) {
                                                    setCarouselEditingForm((prev) => ({
                                                        ...prev,
                                                        buttons: [...(prev.buttons || []), { label: '', url: '' }],
                                                    }));
                                                } else {
                                                    setCarouselForm((prev) => ({
                                                        ...prev,
                                                        buttons: [...(prev.buttons || []), { label: '', url: '' }],
                                                    }));
                                                }
                                            }}
                                        >
                                            + Tambah Button
                                        </button>
                                    </div>
                                    <div className="col-md-6">
                                        <label className="form-label">Gambar</label>
                                        <input
                                            className="form-control"
                                            type="file"
                                            accept="image/*"
                                            onChange={(e) => {
                                                const file = e.target.files?.[0] || null;
                                                if (carouselEditingId) {
                                                    setCarouselEditingImage(file);
                                                } else {
                                                    setCarouselImage(file);
                                                }
                                            }}
                                        />
                                        {(carouselEditingId ? carouselEditingPreviewUrl : carouselPreviewUrl) && (
                                            <div className="preview-wrap mt-2">
                                                <img
                                                    src={carouselEditingId ? carouselEditingPreviewUrl : carouselPreviewUrl}
                                                    alt="Preview"
                                                    className="preview-thumb"
                                                />
                                            </div>
                                        )}
                                    </div>
                                    <div className="col-md-6 d-flex align-items-center">
                                        <div className="form-check form-switch mt-4">
                                            <input
                                                className="form-check-input"
                                                type="checkbox"
                                                checked={carouselEditingId ? !!carouselEditingForm.is_active : carouselForm.is_active}
                                                onChange={(e) => {
                                                    const value = e.target.checked;
                                                    if (carouselEditingId) {
                                                        setCarouselEditingForm((prev) => ({ ...prev, is_active: value }));
                                                    } else {
                                                        setCarouselForm((prev) => ({ ...prev, is_active: value }));
                                                    }
                                                }}
                                            />
                                            <label className="form-check-label">Aktif</label>
                                        </div>
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
            <div className="modal fade" id="newsModal" tabIndex="-1" aria-hidden="true">
                <div className="modal-dialog modal-lg">
                    <div className="modal-content">
                        <form onSubmit={handleNewsSubmit}>
                            <div className="modal-header">
                                <h5 className="modal-title">{editingNewsId ? 'Edit News' : 'Tambah News'}</h5>
                                <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div className="modal-body">
                                <div className="row g-3">
                                    <div className="col-md-6">
                                        <label className="form-label">Judul</label>
                                        <input
                                            className="form-control"
                                            value={newsForm.title}
                                            onChange={(e) => {
                                                const value = e.target.value;
                                                setNewsForm((prev) => ({
                                                    ...prev,
                                                    title: value,
                                                    slug: newsSlugTouched ? prev.slug : toSlug(value),
                                                }));
                                            }}
                                            required
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label className="form-label">Slug</label>
                                        <input
                                            className="form-control"
                                            value={newsForm.slug}
                                            onChange={(e) => {
                                                setNewsSlugTouched(true);
                                                setNewsForm((prev) => ({ ...prev, slug: toSlug(e.target.value) }));
                                            }}
                                            required
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label className="form-label">Kategori</label>
                                        <input
                                            className="form-control"
                                            value={newsForm.category}
                                            onChange={(e) => setNewsForm((prev) => ({ ...prev, category: e.target.value }))}
                                            placeholder="Capital Market, M&A, dll"
                                        />
                                    </div>
                                    <div className="col-12">
                                        <label className="form-label">Ringkasan</label>
                                        <div className="quill-shell">
                                            <div ref={summaryEditorRef} />
                                        </div>
                                    </div>
                                    <div className="col-12">
                                        <label className="form-label">Isi</label>
                                        <div className="quill-shell">
                                            <div ref={bodyEditorRef} />
                                        </div>
                                    </div>
                                    <div className="col-md-6">
                                        <label className="form-label">Status</label>
                                        <select
                                            className="form-select"
                                            value={newsForm.status}
                                            onChange={(e) => setNewsForm((prev) => ({ ...prev, status: e.target.value }))}
                                        >
                                            <option value="draft">Draft</option>
                                            <option value="published">Published</option>
                                        </select>
                                    </div>
                                    <div className="col-md-6">
                                        <label className="form-label">Tanggal Publish</label>
                                        <input
                                            className="form-control"
                                            type="datetime-local"
                                            value={newsForm.published_at}
                                            onChange={(e) => setNewsForm((prev) => ({ ...prev, published_at: e.target.value }))}
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label className="form-label">Cover Image</label>
                                        <input
                                            className="form-control"
                                            type="file"
                                            accept="image/*"
                                            onChange={(e) => setNewsImage(e.target.files?.[0] || null)}
                                        />
                                        {newsPreviewUrl && (
                                            <div className="preview-wrap mt-2">
                                                <img src={newsPreviewUrl} alt="Preview" className="preview-thumb" />
                                            </div>
                                        )}
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
            <div className="modal fade" id="serviceModal" tabIndex="-1" aria-hidden="true">
                <div className="modal-dialog modal-lg">
                    <div className="modal-content">
                        <form onSubmit={handleServiceSubmit}>
                            <div className="modal-header">
                                <h5 className="modal-title">{editingServiceId ? 'Edit Service' : 'Tambah Service'}</h5>
                                <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div className="modal-body">
                                <div className="row g-3">
                                    <div className="col-md-6">
                                        <label className="form-label">Service</label>
                                        <input
                                            className="form-control"
                                            value={serviceForm.title}
                                            onChange={(e) => setServiceForm((prev) => ({ ...prev, title: e.target.value }))}
                                            required
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label className="form-label">Urutan</label>
                                        <input
                                            className="form-control"
                                            type="number"
                                            min="0"
                                            value={serviceForm.sort_order}
                                            onChange={(e) => setServiceForm((prev) => ({ ...prev, sort_order: Number(e.target.value) }))}
                                        />
                                    </div>
                                    <div className="col-12">
                                        <label className="form-label">Deskripsi</label>
                                        <SunEditor
                                            setContents={serviceForm.description}
                                            onChange={(value) => setServiceForm((prev) => ({ ...prev, description: value }))}
                                            height="220px"
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
                                    <div className="col-md-6">
                                        <label className="form-label">Cover Image</label>
                                        <input
                                            className="form-control"
                                            type="file"
                                            accept="image/*"
                                            onChange={(e) => setServiceImage(e.target.files?.[0] || null)}
                                        />
                                        {servicePreviewUrl && (
                                            <div className="preview-wrap mt-2">
                                                <img src={servicePreviewUrl} alt="Preview" className="preview-thumb" />
                                            </div>
                                        )}
                                    </div>
                                    <div className="col-md-6 d-flex align-items-center">
                                        <div className="form-check form-switch mt-4">
                                            <input
                                                className="form-check-input"
                                                type="checkbox"
                                                checked={serviceForm.is_active}
                                                onChange={(e) => setServiceForm((prev) => ({ ...prev, is_active: e.target.checked }))}
                                            />
                                            <label className="form-check-label">Aktif</label>
                                        </div>
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
            <div className="modal fade" id="teamModal" tabIndex="-1" aria-hidden="true">
                <div className="modal-dialog modal-lg">
                    <div className="modal-content">
                        <form onSubmit={handleTeamSubmit}>
                            <div className="modal-header">
                                <h5 className="modal-title">{editingTeamId ? 'Edit Team' : 'Tambah Team'}</h5>
                                <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div className="modal-body">
                                <div className="row g-3">
                                    <div className="col-md-6">
                                        <label className="form-label">Nama</label>
                                        <input
                                            className="form-control"
                                            value={teamForm.name}
                                            onChange={(e) => setTeamForm((prev) => ({ ...prev, name: e.target.value }))}
                                            required
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label className="form-label">Jabatan</label>
                                        <input
                                            className="form-control"
                                            value={teamForm.position}
                                            onChange={(e) => setTeamForm((prev) => ({ ...prev, position: e.target.value }))}
                                            placeholder="CEO, CFO, dll"
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label className="form-label">Description</label>
                                        <textarea
                                            className="form-control"
                                            rows="3"
                                            value={teamForm.description}
                                            onChange={(e) => setTeamForm((prev) => ({ ...prev, description: e.target.value }))}
                                            placeholder="Short professional summary"
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label className="form-label">Urutan</label>
                                        <input
                                            className="form-control"
                                            type="number"
                                            min="0"
                                            value={teamForm.sort_order}
                                            onChange={(e) => setTeamForm((prev) => ({ ...prev, sort_order: Number(e.target.value) }))}
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label className="form-label">Foto</label>
                                        <input
                                            className="form-control"
                                            type="file"
                                            accept="image/*"
                                            onChange={(e) => setTeamImage(e.target.files?.[0] || null)}
                                        />
                                        {teamPreviewUrl && (
                                            <div className="preview-wrap mt-2">
                                                <img src={teamPreviewUrl} alt="Preview" className="preview-thumb" />
                                            </div>
                                        )}
                                    </div>
                                    <div className="col-md-6 d-flex align-items-center">
                                        <div className="form-check form-switch mt-4">
                                            <input
                                                className="form-check-input"
                                                type="checkbox"
                                                checked={teamForm.is_active}
                                                onChange={(e) => setTeamForm((prev) => ({ ...prev, is_active: e.target.checked }))}
                                            />
                                            <label className="form-check-label">Aktif</label>
                                        </div>
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
            <div className="modal fade" id="imagePreviewModal" tabIndex="-1" aria-hidden="true">
                <div className="modal-dialog modal-dialog-centered modal-lg">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h5 className="modal-title">Preview Gambar</h5>
                            <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div className="modal-body text-center">
                            {previewImage && (
                                <img src={previewImage} alt="Preview" className="preview-image" />
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
