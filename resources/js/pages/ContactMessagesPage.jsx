import React, { useEffect, useState } from 'react';
import Swal from 'sweetalert2';
import { swalDefaults } from '../utils/swal';

export default function ContactMessagesPage({ authApi }) {
    const [messages, setMessages] = useState([]);
    const [loading, setLoading] = useState(false);
    const [activeMessage, setActiveMessage] = useState(null);

    const loadMessages = () => {
        setLoading(true);
        authApi.get('/contacts')
            .then((res) => setMessages(res.data.messages || []))
            .finally(() => setLoading(false));
    };

    useEffect(() => {
        loadMessages();
    }, []);

    const openMessage = (message) => {
        setActiveMessage(message);
        const modalEl = document.getElementById('contactMessageModal');
        if (window.bootstrap && modalEl) {
            window.bootstrap.Modal.getOrCreateInstance(modalEl).show();
        }
    };

    const handleDelete = async (message) => {
        const result = await Swal.fire({
            ...swalDefaults,
            icon: 'warning',
            title: 'Hapus pesan?',
            text: `Pesan dari ${message.name} akan dihapus.`,
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal',
        });
        if (!result.isConfirmed) return;

        await authApi.delete(`/contacts/${message.id}`);
        setMessages((prev) => prev.filter((row) => row.id !== message.id));
    };

    return (
        <div className="content">
            <div className="page-header">
                <h1>Contacts</h1>
                <p>Pesan yang masuk dari halaman kontak.</p>
            </div>
            {loading ? (
                <p className="text-muted">Memuat pesan...</p>
            ) : messages.length === 0 ? (
                <p className="text-muted">Belum ada pesan.</p>
            ) : (
                <div className="table-responsive">
                    <table className="table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {messages.map((message) => (
                                <tr key={message.id}>
                                    <td>{message.name}</td>
                                    <td>{message.email}</td>
                                    <td>{message.subject || '-'}</td>
                                    <td>{new Date(message.created_at).toLocaleString('id-ID')}</td>
                                    <td>
                                        <button
                                            className="btn btn-sm btn-outline-primary me-2"
                                            type="button"
                                            onClick={() => openMessage(message)}
                                        >
                                            Lihat
                                        </button>
                                        <button
                                            className="btn btn-sm btn-outline-danger"
                                            type="button"
                                            onClick={() => handleDelete(message)}
                                        >
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            )}

            <div className="modal fade" id="contactMessageModal" tabIndex="-1" aria-hidden="true">
                <div className="modal-dialog modal-lg">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h5 className="modal-title">Detail Pesan</h5>
                            <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div className="modal-body">
                            {activeMessage ? (
                                <>
                                    <p><strong>Nama:</strong> {activeMessage.name}</p>
                                    <p><strong>Email:</strong> {activeMessage.email}</p>
                                    <p><strong>Subject:</strong> {activeMessage.subject || '-'}</p>
                                    <p><strong>Message:</strong></p>
                                    <div className="border rounded p-3 bg-light">
                                        {activeMessage.message}
                                    </div>
                                </>
                            ) : (
                                <p className="text-muted">Tidak ada data.</p>
                            )}
                        </div>
                        <div className="modal-footer">
                            <button type="button" className="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
