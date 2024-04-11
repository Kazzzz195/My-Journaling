import LoginLinks from '@/app/LoginLinks'
import Link from "next/link"

export const metadata = {
    title: 'Journal app',
}

const Home = () => {
    return (
        <>
        <LoginLinks />
        <div style={{backgroundColor:'MediumPurple', minHeight: '100vh'}}> 
        <main className="w-full py-6 sm:py-12 md:py-24 lg:py-32 xl:py-48">
        <div className="container mx-auto px-4 md:px-6 lg:max-w-none">
            <div className="grid gap-6 lg:grid-cols-[1fr_400px] lg:gap-12 xl:grid-cols-[1fr_600px]">
            <div className="flex flex-col justify-center space-y-4">
                <div className="space-y-5 ml-5">
                <h1 className="text-4xl font-bold text-white tracking-tighter sm:text-6xl xl:text-7xl">
                Capture Your Life, One Day at a Time
                </h1>
                <p className="max-w-[600px] font-bold tracking-tighter bg-clip-text text-xl from-white ">
                Our platform offers daily prompts and inspiration to help you start writing, making it easier for you to maintain a consistent journaling habit.
                </p>
                </div>
                <div className="flex flex-col gap-3 ml-4 mt-4 sm:flex-row">
                <Link
                    className="inline-flex h-12 items-center justify-center rounded-md bg-gray-900 px-8 text-base font-medium text-gray-50 shadow transition-colors hover:bg-gray-900/90 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-gray-950 disabled:pointer-events-none disabled:opacity-50 dark:bg-gray-50 dark:text-gray-900 dark:hover:bg-gray-50/90 dark:focus-visible:ring-gray-300"
                    href="/register"
                >
                    Sign up
                </Link>
                <Link
                    className="inline-flex h-12 items-center justify-center rounded-md border border-gray-200 bg-white px-8 text-base font-medium shadow-sm transition-colors hover:bg-gray-100 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-gray-950 disabled:pointer-events-none disabled:opacity-50 dark:border-gray-800 dark:bg-gray-950 dark:hover:bg-gray-800 dark:hover:text-gray-50 dark:focus-visible:ring-gray-300"
                    href="/login"
                >
                    Sign in
                </Link>
                </div>
            </div>
            </div>
        </div>
        </main>
        </div>
        </>
    )
}

export default Home

