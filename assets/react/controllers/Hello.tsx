import React from 'react'
import { Button } from '../components/ui/button'

interface HelloProps {
    fullName: string;
}

export default function (props: HelloProps) {
    return (
        <div className="flex flex-col items-center justify-center h-screen">
            <div className="p-4 border shadow-sm rounded-md font-bold text-2xl inline-flex gap-4">
                <span>Hello { props.fullName }</span>
                <Button>Let's shine !</Button>
            </div>
        </div>
    )
}
