import React, { useEffect, useRef, useState } from 'react';
import Swal from 'sweetalert2';
import Quill from 'quill';
import { ICONS } from '../components/IconMap';
import { swalDefaults } from '../utils/swal';

export default function SettingsPage({ authApi, onSettingsUpdated }) {
    const [form, setForm] = useState({
        company_name: '',
        address: '',
        email: '',
        phone: '',
        about_us: '',
        core_values: '',
        approach: '',
        operating_hours: '',
        business_type: '',
        seo_title: '',
        seo_description: '',
    });
    const [logoFile, setLogoFile] = useState(null);
    const [logoUrl, setLogoUrl] = useState(null);
    const [seoOgFile, setSeoOgFile] = useState(null);
    const [seoOgUrl, setSeoOgUrl] = useState(null);
    const [seoOgPreview, setSeoOgPreview] = useState(null);
    const [seoOgRemoved, setSeoOgRemoved] = useState(false);
    const seoOgInputRef = useRef(null);
    const aboutEditorRef = useRef(null);
    const coreValuesEditorRef = useRef(null);
    const approachEditorRef = useRef(null);
    const aboutQuillRef = useRef(null);
    const coreValuesQuillRef = useRef(null);
    const approachQuillRef = useRef(null);
    const [headerFiles, setHeaderFiles] = useState({
        home: null,
        about: null,
        services: null,
        news: null,
    });
    const [headerUrls, setHeaderUrls] = useState({
        home: null,
        about: null,
        services: null,
        news: null,
    });
    const [headerRemovals, setHeaderRemovals] = useState({
        home: false,
        about: false,
        services: false,
        news: false,
    });
    const [headerPreviewUrls, setHeaderPreviewUrls] = useState({
        home: null,
        about: null,
        services: null,
        news: null,
    });
    const headerInputRefs = {
        home: useRef(null),
        about: useRef(null),
        services: useRef(null),
        news: useRef(null),
    };
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        authApi.get('/settings')
            .then((res) => {
                const settings = res.data.settings || {};
                setForm({
                    company_name: settings.company_name || '',
                    address: settings.address || '',
                    email: settings.email || '',
                    phone: settings.phone || '',
                    about_us: settings.about_us || '',
                    core_values: settings.core_values || '',
                    approach: settings.approach || '',
                    operating_hours: settings.operating_hours || '',
                    business_type: settings.business_type || '',
                    seo_title: settings.seo_title || '',
                    seo_description: settings.seo_description || '',
                });
                setLogoUrl(settings.logo_url || null);
                setSeoOgUrl(settings.seo_og_image_url || null);
                setHeaderUrls({
                    home: settings.header_home_url || null,
                    about: settings.header_about_url || null,
                    services: settings.header_services_url || null,
                    news: settings.header_news_url || null,
                });
                setHeaderRemovals({
                    home: false,
                    about: false,
                    services: false,
                    news: false,
                });
                setSeoOgRemoved(false);
                if (onSettingsUpdated) {
                    onSettingsUpdated(settings);
                }
            })
            .catch(async (err) => {
                const errorMessage = err?.response?.data?.message || 'Tidak bisa memuat setting.';
                await Swal.fire({
                    ...swalDefaults,
                    icon: 'error',
                    title: 'Gagal',
                    text: errorMessage,
                    confirmButtonText: 'OK',
                });
            })
            .finally(() => setLoading(false));
    }, [authApi, onSettingsUpdated]);

    useEffect(() => {
        if (loading) return;
        if (aboutEditorRef.current && !aboutQuillRef.current) {
            aboutQuillRef.current = new Quill(aboutEditorRef.current, {
                theme: 'snow',
                placeholder: 'Tulis About Us...',
            });
            aboutQuillRef.current.on('text-change', () => {
                const html = aboutQuillRef.current.root.innerHTML;
                setForm((prev) => ({ ...prev, about_us: html }));
            });
        }
        if (coreValuesEditorRef.current && !coreValuesQuillRef.current) {
            coreValuesQuillRef.current = new Quill(coreValuesEditorRef.current, {
                theme: 'snow',
                placeholder: 'Tulis Our Core Values...',
            });
            coreValuesQuillRef.current.on('text-change', () => {
                const html = coreValuesQuillRef.current.root.innerHTML;
                setForm((prev) => ({ ...prev, core_values: html }));
            });
        }
        if (approachEditorRef.current && !approachQuillRef.current) {
            approachQuillRef.current = new Quill(approachEditorRef.current, {
                theme: 'snow',
                placeholder: 'Tulis Our Approach...',
            });
            approachQuillRef.current.on('text-change', () => {
                const html = approachQuillRef.current.root.innerHTML;
                setForm((prev) => ({ ...prev, approach: html }));
            });
        }
    }, [loading]);

    useEffect(() => {
        if (aboutQuillRef.current) {
            const current = aboutQuillRef.current.root.innerHTML;
            if (current !== (form.about_us || '')) {
                aboutQuillRef.current.root.innerHTML = form.about_us || '';
            }
        }
        if (coreValuesQuillRef.current) {
            const current = coreValuesQuillRef.current.root.innerHTML;
            if (current !== (form.core_values || '')) {
                coreValuesQuillRef.current.root.innerHTML = form.core_values || '';
            }
        }
        if (approachQuillRef.current) {
            const current = approachQuillRef.current.root.innerHTML;
            if (current !== (form.approach || '')) {
                approachQuillRef.current.root.innerHTML = form.approach || '';
            }
        }
    }, [form.about_us, form.approach, form.core_values]);

    useEffect(() => {
        const next = {};
        Object.entries(headerFiles).forEach(([key, file]) => {
            if (file) {
                next[key] = URL.createObjectURL(file);
            }
        });
        setHeaderPreviewUrls(next);
        return () => {
            Object.values(next).forEach((url) => {
                if (url) URL.revokeObjectURL(url);
            });
        };
    }, [headerFiles]);

    useEffect(() => {
        if (!seoOgFile) {
            setSeoOgPreview(null);
            return undefined;
        }
        const nextUrl = URL.createObjectURL(seoOgFile);
        setSeoOgPreview(nextUrl);
        return () => URL.revokeObjectURL(nextUrl);
    }, [seoOgFile]);

    const updateField = (key, value) => {
        setForm((prev) => ({ ...prev, [key]: value }));
    };

    const handleRemoveHeader = async (key) => {
        const result = await Swal.fire({
            ...swalDefaults,
            icon: 'warning',
            title: 'Hapus gambar?',
            text: 'Gambar header akan dihapus.',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            confirmButtonColor: '#dc3545',
            cancelButtonText: 'Batal',
        });
        if (!result.isConfirmed) return;
        setHeaderFiles((prev) => ({ ...prev, [key]: null }));
        setHeaderUrls((prev) => ({ ...prev, [key]: null }));
        setHeaderRemovals((prev) => ({ ...prev, [key]: true }));
        if (headerInputRefs[key]?.current) headerInputRefs[key].current.value = '';
    };

    const handleRemoveSeoOg = async () => {
        const result = await Swal.fire({
            ...swalDefaults,
            icon: 'warning',
            title: 'Hapus gambar?',
            text: 'OG image SEO akan dihapus.',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            confirmButtonColor: '#dc3545',
            cancelButtonText: 'Batal',
        });
        if (!result.isConfirmed) return;
        setSeoOgFile(null);
        setSeoOgUrl(null);
        setSeoOgRemoved(true);
        if (seoOgInputRef.current) seoOgInputRef.current.value = '';
    };

    const handleSave = async (e) => {
        e.preventDefault();
        const payload = new FormData();
        Object.entries(form).forEach(([key, value]) => payload.append(key, value));
        if (aboutQuillRef.current) {
            payload.set('about_us', aboutQuillRef.current.root.innerHTML || '');
        }
        if (coreValuesQuillRef.current) {
            payload.set('core_values', coreValuesQuillRef.current.root.innerHTML || '');
        }
        if (approachQuillRef.current) {
            payload.set('approach', approachQuillRef.current.root.innerHTML || '');
        }
        if (logoFile) {
            payload.append('logo', logoFile);
        }
        if (seoOgFile) {
            payload.append('seo_og_image', seoOgFile);
        }
        if (headerFiles.home) payload.append('header_home', headerFiles.home);
        if (headerFiles.about) payload.append('header_about', headerFiles.about);
        if (headerFiles.services) payload.append('header_services', headerFiles.services);
        if (headerFiles.news) payload.append('header_news', headerFiles.news);
        if (seoOgRemoved) payload.append('remove_seo_og_image', '1');
        if (headerRemovals.home) payload.append('remove_header_home', '1');
        if (headerRemovals.about) payload.append('remove_header_about', '1');
        if (headerRemovals.services) payload.append('remove_header_services', '1');
        if (headerRemovals.news) payload.append('remove_header_news', '1');
        try {
            const res = await authApi.post('/settings', payload, {
                headers: { 'Content-Type': 'multipart/form-data' },
            });
            setLogoUrl(res.data.settings?.logo_url || logoUrl);
            setSeoOgUrl(res.data.settings?.seo_og_image_url || seoOgUrl);
            setHeaderUrls({
                home: res.data.settings?.header_home_url || headerUrls.home,
                about: res.data.settings?.header_about_url || headerUrls.about,
                services: res.data.settings?.header_services_url || headerUrls.services,
                news: res.data.settings?.header_news_url || headerUrls.news,
            });
            setLogoFile(null);
            setSeoOgFile(null);
            setSeoOgRemoved(false);
            setHeaderFiles({
                home: null,
                about: null,
                services: null,
                news: null,
            });
            setHeaderRemovals({
                home: false,
                about: false,
                services: false,
                news: false,
            });
            if (onSettingsUpdated) {
                onSettingsUpdated(res.data.settings || {});
            }
            if (res.data.settings?.logo_url) {
                let link = document.querySelector("link[rel~='icon']");
                if (!link) {
                    link = document.createElement('link');
                    link.rel = 'icon';
                    document.head.appendChild(link);
                }
                link.href = res.data.settings.logo_url;
            }
            await Swal.fire({
                ...swalDefaults,
                icon: 'success',
                title: 'Tersimpan',
                text: 'Setting website berhasil disimpan.',
                confirmButtonText: 'OK',
            });
        } catch (err) {
            const errorMessage = err?.response?.data?.message || 'Gagal menyimpan setting.';
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
                    <span className="icon">{ICONS.settings}</span>
                    Settings Website
                </h1>
                <p>Isi informasi utama company profile.</p>
            </div>
            <div className="panel">
                {loading && <p>Memuat setting...</p>}
                {!loading && (
                    <form className="content-form" onSubmit={handleSave}>
                        <label>
                            Nama Company
                            <input
                                value={form.company_name}
                                onChange={(e) => updateField('company_name', e.target.value)}
                                placeholder="Nama perusahaan"
                            />
                        </label>
                        <label>
                            Alamat
                            <input
                                value={form.address}
                                onChange={(e) => updateField('address', e.target.value)}
                                placeholder="Alamat lengkap"
                            />
                        </label>
                        <label>
                            Email
                            <input
                                type="email"
                                value={form.email}
                                onChange={(e) => updateField('email', e.target.value)}
                                placeholder="email@company.com"
                            />
                        </label>
                        <label>
                            Telpon
                            <input
                                value={form.phone}
                                onChange={(e) => updateField('phone', e.target.value)}
                                placeholder="021-xxxxxxx"
                            />
                        </label>
                        <label>
                            About Us
                            <div className="quill-shell">
                                <div ref={aboutEditorRef} />
                            </div>
                        </label>
                        <label>
                            Our Core Values
                            <div className="quill-shell">
                                <div ref={coreValuesEditorRef} />
                            </div>
                        </label>
                        <label>
                            Our Approach
                            <div className="quill-shell">
                                <div ref={approachEditorRef} />
                            </div>
                        </label>
                        <label>
                            Jam Operasional
                            <input
                                value={form.operating_hours}
                                onChange={(e) => updateField('operating_hours', e.target.value)}
                                placeholder="Senin - Jumat, 08:00 - 17:00"
                            />
                        </label>
                        <label>
                            Tipe Business
                            <input
                                value={form.business_type}
                                onChange={(e) => updateField('business_type', e.target.value)}
                                placeholder="Manufaktur, Konsultan, dll"
                            />
                        </label>
                        <label>
                            SEO Title
                            <input
                                value={form.seo_title}
                                onChange={(e) => updateField('seo_title', e.target.value)}
                                placeholder="Judul untuk mesin pencari"
                            />
                        </label>
                        <label>
                            SEO Description
                            <textarea
                                value={form.seo_description}
                                onChange={(e) => updateField('seo_description', e.target.value)}
                                rows="3"
                                placeholder="Deskripsi singkat untuk mesin pencari"
                            />
                        </label>
                        <label>
                            SEO OG Image
                            <input
                                ref={seoOgInputRef}
                                type="file"
                                accept="image/*"
                                onChange={(e) => {
                                    const file = e.target.files?.[0] || null;
                                    setSeoOgFile(file);
                                    if (file) setSeoOgRemoved(false);
                                }}
                            />
                            <div className="muted">Rekomendasi: 1200 x 630 px</div>
                        </label>
                        {(seoOgPreview || seoOgUrl) && (
                            <div className="logo-preview">
                                <img src={seoOgPreview || seoOgUrl} alt="SEO OG" />
                                <div className="muted">Rekomendasi: 1200 x 630 px</div>
                                <button
                                    type="button"
                                    className="btn btn-outline-secondary btn-sm"
                                    onClick={handleRemoveSeoOg}
                                >
                                    Hapus
                                </button>
                            </div>
                        )}
                        <label>
                            Logo
                            <input
                                type="file"
                                accept="image/*"
                                onChange={(e) => setLogoFile(e.target.files?.[0] || null)}
                            />
                            <div className="muted">Rekomendasi: 256 x 256 px</div>
                        </label>
                        {logoUrl && (
                            <div className="logo-preview">
                                <img src={logoUrl} alt="Logo" />
                                <div className="muted">Rekomendasi: 256 x 256 px</div>
                            </div>
                        )}
                        <div className="header-section">
                            <h4>Header Image Per Halaman</h4>
                            <div className="header-grid">
                                {[
                                    ['home', 'Home'],
                                    ['about', 'About'],
                                    ['services', 'Services'],
                                    ['news', 'News'],
                                ].map(([key, label]) => (
                                    <div className="header-item" key={key}>
                                        <label>
                                            {label}
                                            <input
                                                ref={headerInputRefs[key]}
                                                type="file"
                                                accept="image/*"
                                                onChange={(e) => {
                                                    const file = e.target.files?.[0] || null;
                                                    setHeaderFiles((prev) => ({ ...prev, [key]: file }));
                                                    if (file) {
                                                        setHeaderRemovals((prev) => ({ ...prev, [key]: false }));
                                                    }
                                                }}
                                            />
                                            <div className="muted">Rekomendasi: 1920 x 600 px</div>
                                        </label>
                                        {(headerPreviewUrls[key] || headerUrls[key]) && (
                                            <div className="logo-preview">
                                                <img src={headerPreviewUrls[key] || headerUrls[key]} alt={`Header ${label}`} />
                                                <div className="muted">Rekomendasi: 1920 x 600 px</div>
                                            </div>
                                        )}
                                        <button
                                            type="button"
                                            className="btn btn-outline-secondary btn-sm"
                                            onClick={() => handleRemoveHeader(key)}
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                ))}
                            </div>
                        </div>
                        <div className="actions">
                            <button type="submit">Simpan</button>
                        </div>
                    </form>
                )}
            </div>
        </div>
    );
}
