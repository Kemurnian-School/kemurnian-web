import Sidebar from '@AdminComponents/Sidebar'
import Snackbar from '@AdminComponents/Snackbar';

export default function AdminLayout({ children }: { children: React.ReactNode }) {
    return (
        <div className="min-h-screen flex">
            <Sidebar />
            <Snackbar />
            <main className="flex-1">
                {children}
            </main>
        </div>
    )
}
