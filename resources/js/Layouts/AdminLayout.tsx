import Sidebar from '@AdminComponents/Sidebar'

export default function AdminLayout({ children }: { children: React.ReactNode }) {
  return (
    <div className="min-h-screen flex">
      <Sidebar />
      <main className="flex-1">
        {children}
      </main>
    </div>
  )
}
