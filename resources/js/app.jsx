import './bootstrap';
import 'quill/dist/quill.snow.css';
import React, { useEffect, useMemo, useState } from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter, Navigate, Route, Routes } from 'react-router-dom';
import Swal from 'sweetalert2';
import api from './api/client';
import AuthPage from './pages/AuthPage';
import CmsLayout from './layouts/CmsLayout';
import { swalDefaults } from './utils/swal';

function ProtectedRoute({ loading, token, children }) {
    if (loading) {
        return (
            <div className="page">
                <main className="card">
                    <p>Memuat sesi...</p>
                </main>
            </div>
        );
    }
    if (!token) {
        return <Navigate to="/login" replace />;
    }
    return children;
}

function App() {
    const [user, setUser] = useState(null);
    const [token, setToken] = useState(localStorage.getItem('auth_token') || '');
    const [loading, setLoading] = useState(true);
    const [permissions, setPermissions] = useState({});
    const [canManageUsers, setCanManageUsers] = useState(false);
    const [sections, setSections] = useState({});
    const [appSettings, setAppSettings] = useState({ company_name: null });

    const authApi = useMemo(() => {
        const instance = api;
        instance.defaults.headers.common.Authorization = token ? `Bearer ${token}` : '';
        return instance;
    }, [token]);

    useEffect(() => {
        if (!token) {
            setUser(null);
            setLoading(false);
            return;
        }
        setLoading(true);
        authApi.get('/auth/me')
            .then((res) => setUser(res.data.user))
            .catch(() => {
                localStorage.removeItem('auth_token');
                setToken('');
            })
            .finally(() => setLoading(false));
    }, [authApi, token]);

    useEffect(() => {
        if (!token) {
            setPermissions({});
            setSections({});
            setAppSettings({ company_name: null });
            return;
        }
        Promise.all([
            authApi.get('/permissions/me'),
            authApi.get('/sections'),
        ]).then(([permRes, sectionRes]) => {
            setPermissions(permRes.data.permissions || {});
            setCanManageUsers(!!permRes.data.can_manage_users);
            const map = {};
            (sectionRes.data.sections || []).forEach((section) => {
                map[section.slug] = section;
            });
            setSections(map);
        });
    }, [authApi, token]);

    useEffect(() => {
        if (!token) {
            return;
        }
        authApi.get('/settings')
            .then((res) => {
                const settings = res.data.settings || {};
                setAppSettings(settings);
                if (settings.logo_url) {
                    let link = document.querySelector("link[rel~='icon']");
                    if (!link) {
                        link = document.createElement('link');
                        link.rel = 'icon';
                        document.head.appendChild(link);
                    }
                    link.href = settings.logo_url;
                }
            })
            .catch(() => {
                setAppSettings({ company_name: null });
            });
    }, [authApi, token]);

    const handleLogin = (authToken, authUser) => {
        localStorage.setItem('auth_token', authToken);
        setToken(authToken);
        setUser(authUser);
    };

    const handleLogout = async () => {
        if (!token) return;
        await authApi.post('/auth/logout');
        localStorage.removeItem('auth_token');
        setToken('');
        setUser(null);
        setPermissions({});
        setSections({});
        setCanManageUsers(false);
        setAppSettings({ company_name: null });
    };

    const handleToggleVisibility = async (slug) => {
        try {
            const res = await authApi.patch(`/sections/${slug}/visibility`);
            setSections((prev) => ({
                ...prev,
                [slug]: {
                    ...prev[slug],
                    is_visible: res.data.is_visible,
                },
            }));
        } catch (err) {
            const errorMessage = err?.response?.data?.message || 'Akses ditolak.';
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
        <BrowserRouter basename="/admin">
            <Routes>
                <Route path="/" element={<Navigate to={token ? '/dashboard' : '/login'} replace />} />
                <Route path="/login" element={<AuthPage mode="login" onLogin={handleLogin} />} />
                <Route path="/register" element={<Navigate to="/login" replace />} />
                <Route
                    path="/*"
                    element={(
                        <ProtectedRoute loading={loading} token={token}>
                            <CmsLayout
                                user={user}
                                permissions={permissions}
                                sections={sections}
                                onToggleVisibility={handleToggleVisibility}
                                onLogout={handleLogout}
                                authApi={authApi}
                                canManageUsers={canManageUsers}
                                appSettings={appSettings}
                                setAppSettings={setAppSettings}
                            />
                        </ProtectedRoute>
                    )}
                />
            </Routes>
        </BrowserRouter>
    );
}

createRoot(document.getElementById('app')).render(<App />);
