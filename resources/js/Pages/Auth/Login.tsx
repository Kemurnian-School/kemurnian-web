import { FormEventHandler } from 'react'
import { Head, useForm } from '@inertiajs/react'

export default function Login() {
  const { data, setData, post, processing, errors } = useForm({
    email: '',
    password: '',
    remember: false,
  })

  const submit: FormEventHandler = (e) => {
    e.preventDefault()
    post('/login')
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-50">
      <Head title="Admin Login" />
      <form
        onSubmit={submit}
        className="space-y-4 bg-white p-6 rounded shadow w-full max-w-sm"
      >
        <h2 className="text-2xl font-bold">Admin Login</h2>
        <div>
          <label htmlFor="email" className="block font-medium">Email:</label>
          <input
            id="email"
            name="email"
            type="email"
            required
            className="border p-2 w-full"
            value={data.email}
            onChange={(e) => setData('email', e.target.value)}
          />
          {errors.email && (
            <p className="text-sm text-red-600 mt-1">{errors.email}</p>
          )}
        </div>
        <div>
          <label htmlFor="password" className="block font-medium">Password:</label>
          <input
            id="password"
            name="password"
            type="password"
            required
            className="border p-2 w-full"
            value={data.password}
            onChange={(e) => setData('password', e.target.value)}
          />
        </div>
        <button
          type="submit"
          disabled={processing}
          className="px-4 py-2 bg-blue-600 text-white rounded"
        >
          Log In
        </button>
      </form>
    </div>
  )
}
