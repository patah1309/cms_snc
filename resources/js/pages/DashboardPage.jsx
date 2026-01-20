import React from 'react';
import { ICONS } from '../components/IconMap';

export default function DashboardPage({ user, onLogout }) {
    return (
        <div className="content">
            <div className="page-header">
                <h1 className="with-icon">
                    <span className="icon">{ICONS.dashboard}</span>
                    Dashboard CMS
                </h1>
                <p>Ringkasan akses dan status konten.</p>
            </div>
            <div className="card-grid">
                <div className="mini-card">
                    <h3>Selamat datang</h3>
                    <p>Halo, <strong>{user?.name}</strong></p>
                </div>
                <div className="mini-card">
                    <h3>Role</h3>
                    <p>{user?.role}</p>
                </div>
            </div>
            <div className="panel">
                <div className="actions">
                    <button className="ghost" onClick={onLogout}>Logout</button>
                </div>
            </div>
        </div>
    );
}
