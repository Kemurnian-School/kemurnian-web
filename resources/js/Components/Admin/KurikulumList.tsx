import { useState } from 'react'
import { Link, router } from '@inertiajs/react'
import { RiBookOpenLine, RiEditLine, RiDeleteBinLine } from '@remixicon/react'
import ConfirmationModal from '@AdminComponents/ConfirmationModal'

interface Kurikulum {
  id: number
  title: string
  body: string
  created_at: string
}

export default function KurikulumList({ initialKurikulums }: { initialKurikulums: Kurikulum[] }) {
  const [kurikulums, setKurikulums] = useState<Kurikulum[]>(initialKurikulums)
  const [itemToDelete, setItemToDelete] = useState<Kurikulum | null>(null)

  function handleConfirmDelete() {
    if (!itemToDelete) return
    router.delete(`/admin/kurikulum/${itemToDelete.id}`, {
      onSuccess: () => {
        setKurikulums(prev => prev.filter(k => k.id !== itemToDelete!.id))
        setItemToDelete(null)
      }
    })
  }

  function stripHtml(html?: string | null) {
    if (!html) return ''
    return html.replace(/<[^>]*>/g, '').replace(/&nbsp;/g, ' ').trim()
  }

  return (
    <div>
      {kurikulums.length === 0 ? (
        <div className="flex flex-col items-center justify-center">
          <RiBookOpenLine size={58} color="grey" />
          <h3 className="text-lg font-medium text-gray-500 mb-2">No curriculum yet</h3>
        </div>
      ) : (
        <div className="flex flex-col space-y-4">
          {kurikulums.map((item) => (
            <div
              key={item.id}
              className="flex flex-col md:flex-row justify-between items-start bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200"
            >
              <div className="flex-1 mb-4 md:mb-0">
                <h3 className="text-lg font-semibold text-gray-900 truncate pr-2 mb-2">
                  {item.title}
                </h3>
                <p className="text-gray-600 text-sm leading-relaxed">
                  {stripHtml(item.body).substring(0, 100)}{stripHtml(item.body).length > 100 ? '...' : ''}
                </p>
              </div>

              <article className="flex flex-col justify-end text-white gap-2">
                <div className="flex flex-row gap-2">
                  <Link
                    href={`/admin/kurikulum/edit/${item.id}`}
                    className="px-3 py-2 bg-blue-700 hover:bg-blue-800 active:bg-blue-900 flex justify-end items-center gap-1 rounded-full"
                  >
                    <RiEditLine size={17} />
                    Edit
                  </Link>
                  <button
                    onClick={() => setItemToDelete(item)}
                    className="px-3 py-2 bg-red-700 hover:bg-red-800 active:bg-red-900 flex justify-center items-center gap-1 rounded-full cursor-pointer"
                  >
                    <RiDeleteBinLine size={18} />
                    Delete
                  </button>
                </div>

                <span className="text-sm text-gray-700 text-end mr-1">
                  {new Date(item.created_at).toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric',
                  })}
                </span>
              </article>
            </div>
          ))}
        </div>
      )}

      {itemToDelete && (
        <ConfirmationModal
          item={{ title: itemToDelete.title }}
          onConfirm={handleConfirmDelete}
          onCancel={() => setItemToDelete(null)}
        />
      )}
    </div>
  )
}
