import { useEffect, useState } from 'react'
import { usePage } from '@inertiajs/react'
import { RiCheckboxCircleLine, RiCloseCircleLine } from '@remixicon/react'

export default function Snackbar() {
    const { flash } = usePage<{ flash: { success?: string; error?: string } }>().props
    const [isVisible, setIsVisible] = useState(false)
    const [message, setMessage] = useState('')
    const [success, setSuccess] = useState(true)

    useEffect(() => {
        if (flash?.success) {
            setSuccess(true)
            setMessage(flash.success)
            setIsVisible(true)
            setTimeout(() => setIsVisible(false), 3000)
        } else if (flash?.error) {
            setSuccess(false)
            setMessage(flash.error)
            setIsVisible(true)
            setTimeout(() => setIsVisible(false), 3000)
        }
    }, [flash])

    return (
        <div className={`fixed right-4 z-50 py-3 px-4 rounded-lg shadow-lg ${success ? 'bg-green-300' : 'bg-red-500'} text-white transition-all duration-500 ease-in-out ${isVisible ? 'top-4' : '-top-24'}`}>
            <div className="flex items-center justify-center gap-2 text-green-900">
                {success ? <RiCheckboxCircleLine size={20} /> : <RiCloseCircleLine size={20} className="text-white" />}
                <span>{message}</span>
            </div>
        </div>
    )
}
