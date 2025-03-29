import React from 'react'
import { Button } from '../components/ui/button'

interface HelloProps {
    fullName: string;
}

export default function (props: HelloProps) {
    return (
        <div className="p-4 font-bold text-2xl flex gap-4">
            <span>Hello { props.fullName }</span>
            <Button>Let's shine !</Button>
        </div>
    )
}
