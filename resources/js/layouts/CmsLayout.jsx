import React, { useEffect, useState } from 'react';
import { Navigate, Route, Routes, useLocation, useNavigate } from 'react-router-dom';
import { ICONS } from '../components/IconMap';
import { CONTENT_MENUS, NAV_MENU, SETTINGS_MENU, USERS_MENU } from '../constants/menus';
import AccessPage from '../pages/AccessPage';
import DashboardPage from '../pages/DashboardPage';
import MenuPage from '../pages/MenuPage';
import SectionPage from '../pages/SectionPage';
import SettingsPage from '../pages/SettingsPage';
import UsersPage from '../pages/UsersPage';

export default function CmsLayout({
    user,
    permissions,
    sections,
    onToggleVisibility,
    onLogout,
    authApi,
    canManageUsers,
    appSettings,
    setAppSettings,
}) {
    const navigate = useNavigate();
    const location = useLocation();
    const canView = (slug) => permissions[slug]?.can_view;
    const [collapsed, setCollapsed] = useState(() => {
        const saved = localStorage.getItem('cms_sidebar_collapsed');
        return saved === 'true';
    });
    const [theme, setTheme] = useState(() => {
        return localStorage.getItem('cms_theme') || 'light';
    });
    const [now, setNow] = useState(() => new Date());
    const [adminOpen, setAdminOpen] = useState(false);

    useEffect(() => {
        localStorage.setItem('cms_sidebar_collapsed', String(collapsed));
    }, [collapsed]);

    useEffect(() => {
        localStorage.setItem('cms_theme', theme);
        document.documentElement.setAttribute('data-theme', theme);
    }, [theme]);

    useEffect(() => {
        const timer = setInterval(() => setNow(new Date()), 1000);
        return () => clearInterval(timer);
    }, []);

    const companyName = appSettings?.company_name || 'Company Profile';

    useEffect(() => {
        const path = location.pathname;
        let pageTitle = 'Dashboard';
        if (path.startsWith('/cms/settings')) {
            pageTitle = 'Settings Website';
        } else if (path.startsWith('/cms/menus')) {
            pageTitle = 'Navigation Menu';
        } else if (path.startsWith('/cms/users')) {
            pageTitle = 'Users';
        } else if (path.startsWith('/cms/access')) {
            pageTitle = 'User Access';
        } else if (path.startsWith('/cms/')) {
            const slug = path.replace('/cms/', '').split('/')[0];
            const menu = CONTENT_MENUS.find((item) => item.slug === slug);
            pageTitle = menu ? menu.label : 'CMS';
        }
        document.title = `${companyName} - ${pageTitle}`;
    }, [companyName, location.pathname]);

    return (
        <div className={`cms ${collapsed ? 'collapsed' : ''}`}>
            <aside className="sidebar">
                <div className="brand">
                    <span className="brand-icon">
                        {ICONS.dashboard}
                    </span>
                    <span className="brand-text">{companyName} CMS</span>
                </div>
                <nav>
                    <button
                        className="nav-item"
                        onClick={() => navigate('/dashboard')}
                    >
                        <span className="icon">{ICONS.dashboard}</span>
                        <span className="label">Dashboard</span>
                    </button>
                    {CONTENT_MENUS.filter((item) => canView(item.slug)).map((item) => (
                        <button
                            key={item.slug}
                            className="nav-item"
                            onClick={() => navigate(`/cms/${item.slug}`)}
                        >
                            <span className="icon">{ICONS[item.slug] || ICONS.dashboard}</span>
                            <span className="label">{item.label}</span>
                        </button>
                    ))}
                    {canView(SETTINGS_MENU.slug) && (
                        <button
                            className="nav-item"
                            onClick={() => navigate('/cms/settings')}
                        >
                            <span className="icon">{ICONS.settings}</span>
                            <span className="label">{SETTINGS_MENU.label}</span>
                        </button>
                    )}
                    {canView(NAV_MENU.slug) && (
                        <button
                            className="nav-item"
                            onClick={() => navigate('/cms/menus')}
                        >
                            <span className="icon">{ICONS.menus}</span>
                            <span className="label">{NAV_MENU.label}</span>
                        </button>
                    )}
                    {user?.role === 'super_admin' && (canView(USERS_MENU.slug) || canManageUsers) && (
                        <div className="nav-group">
                            <button
                                className="nav-item nav-parent"
                                type="button"
                                onClick={() => setAdminOpen((prev) => !prev)}
                            >
                                <span className="icon">{ICONS.access}</span>
                                <span className="label">Administrator</span>
                                <span className="nav-caret">
                                    <i className={`fa-solid fa-chevron-${adminOpen ? 'up' : 'down'}`}></i>
                                </span>
                            </button>
                            <div className={`nav-submenu ${adminOpen ? 'open' : ''}`}>
                                {canView(USERS_MENU.slug) && (
                                    <button
                                        className="nav-item nav-child"
                                        onClick={() => navigate('/cms/users')}
                                    >
                                        <span className="icon">{ICONS.users}</span>
                                        <span className="label">{USERS_MENU.label}</span>
                                    </button>
                                )}
                                {canManageUsers && (
                                    <button
                                        className="nav-item nav-child"
                                        onClick={() => navigate('/cms/access')}
                                    >
                                        <span className="icon">{ICONS.access}</span>
                                        <span className="label">User Access</span>
                                    </button>
                                )}
                            </div>
                        </div>
                    )}
                </nav>
            </aside>
            <div className="main">
                <header className="topbar">
                    <div className="topbar-left">
                        <button
                            className="ghost icon-button"
                            onClick={() => setCollapsed((prev) => !prev)}
                            title="Toggle sidebar"
                        >
                            {ICONS.collapse}
                        </button>
                        <div className="topbar-brand">
                            <span className="subtle">
                                {now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}
                                {' '}•{' '}
                                {now.toLocaleTimeString('id-ID')}
                            </span>
                        </div>
                    </div>
                    <div className="dropdown">
                        <button
                            className="btn btn-light dropdown-toggle profile-btn"
                            data-bs-toggle="dropdown"
                            type="button"
                        >
                            <i className="fa-solid fa-circle-user"></i>
                            <span className="label">{user?.name}</span>
                        </button>
                        <ul className="dropdown-menu dropdown-menu-end">
                            <li>
                                <button className="dropdown-item" type="button">
                                    <i className="fa-regular fa-user"></i> Profile
                                </button>
                            </li>
                            <li>
                                <button
                                    className="dropdown-item"
                                    type="button"
                                    onClick={() => setTheme((prev) => (prev === 'dark' ? 'light' : 'dark'))}
                                >
                                    {theme === 'dark' ? (
                                        <>
                                            <i className="fa-solid fa-sun"></i> Light Mode
                                        </>
                                    ) : (
                                        <>
                                            <i className="fa-solid fa-moon"></i> Dark Mode
                                        </>
                                    )}
                                </button>
                            </li>
                            <li><hr className="dropdown-divider" /></li>
                            <li>
                                <button className="dropdown-item text-danger" onClick={onLogout}>
                                    <i className="fa-solid fa-right-from-bracket"></i> Logout
                                </button>
                            </li>
                        </ul>
                    </div>
                </header>
                <div className="main-body">
                    <Routes>
                        <Route path="/dashboard" element={<DashboardPage user={user} onLogout={onLogout} />} />
                        <Route
                            path="/cms/:slug"
                            element={(
                                <SectionPage
                                    permissions={permissions}
                                    sections={sections}
                                    onToggleVisibility={onToggleVisibility}
                                    authApi={authApi}
                                />
                            )}
                        />
                        <Route
                            path="/cms/settings"
                            element={<SettingsPage authApi={authApi} onSettingsUpdated={setAppSettings} />}
                        />
                        <Route path="/cms/menus" element={<MenuPage authApi={authApi} />} />
                        <Route path="/cms/users" element={<UsersPage authApi={authApi} />} />
                        <Route path="/cms/access" element={<AccessPage authApi={authApi} />} />
                        <Route path="*" element={<Navigate to="/dashboard" replace />} />
                    </Routes>
                </div>
                <footer className="cms-footer">
                    <span>Powered by CMS Company</span>
                    <span>© {new Date().getFullYear()}</span>
                </footer>
            </div>
        </div>
    );
}
