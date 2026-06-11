import { useState } from 'react';
import { useForm } from '@inertiajs/react';
import LinkRow from './LinkRow';

interface ItemContactLink {
    id: number;
    name: string;
    school_group: string;
    school_level: string;
    url: string;
}

interface Option {
    value: string;
    label: string;
}

interface Props {
    links: ItemContactLink[];
    schoolGroups: Option[];
    schoolLevels: Option[];
}

export default function ContactLinksList({ links, schoolGroups, schoolLevels }: Props) {
    const [editingId, setEditingId] = useState<number | null>(null);

    const form = useForm({
        name: '',
        school_group: '',
        school_level: '',
        url: '',
    });

    const startEditing = (link: ItemContactLink) => {
        setEditingId(link.id);
        form.setData({
            name: link.name,
            school_group: link.school_group,
            school_level: link.school_level,
            url: link.url,
        });
    };

    const cancelEditing = () => {
        setEditingId(null);
        form.reset();
        form.clearErrors();
    };

    return (
        <div className="border border-gray-200 rounded-lg overflow-hidden shadow-sm">
            <table className="w-full border-separate border-spacing-0">
                <thead className="bg-gray-100">
                    <tr>
                        <th className="p-3 text-left">Name</th>
                        <th className="p-3 text-left">Group</th>
                        <th className="p-3 text-left">Level</th>
                        <th className="p-3 text-left">URL</th>
                        <th className="p-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody className="divide-y divide-gray-200">
                    {links.map(link => (
                        <LinkRow
                            key={link.id}
                            link={link}
                            isEditing={editingId === link.id}
                            form={form}
                            onEdit={startEditing}
                            onCancel={cancelEditing}
                            schoolGroups={schoolGroups}
                            schoolLevels={schoolLevels}
                        />
                    ))}
                </tbody>
            </table>
        </div>
    );
}
