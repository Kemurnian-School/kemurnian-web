import { RiPencilLine, RiDeleteBinFill, RiCloseLine, RiCheckLine } from '@remixicon/react';

interface ItemContactLink {
    id: number;
    name: string;
    school_group: string;
    school_level: string;
    url: string;
}

interface Props {
    link: ItemContactLink;
    isEditing: boolean;
    form: any;
    onEdit: (link: ItemContactLink) => void;
    onCancel: () => void;
    onDelete: (id: number) => void;
    schoolGroups: { value: string; label: string }[];
    schoolLevels: { value: string; label: string }[];
}

export default function LinkRow({
    link,
    isEditing,
    form,
    onEdit,
    onCancel,
    onDelete,
    schoolGroups,
    schoolLevels,
}: Props) {
    const data = isEditing ? form.data : link;

    const inputClass = "w-full border border-gray-300 rounded px-2 py-1 text-sm leading-normal box-border";
    const selectClass = "w-full border border-gray-300 rounded px-2 py-1 text-sm leading-normal box-border bg-white";
    const tdClass = "p-0 h-11 overflow-hidden align-middle";
    const innerClass = "h-full flex items-center px-3";

    return (
        <tr className="hover:bg-gray-50">
            <td className={`${tdClass} w-[24%]`}>
                <div className={innerClass}>
                    {isEditing ? (
                        <input
                            type="text"
                            value={data.name}
                            onChange={(e) => form.setData("name", e.target.value)}
                            className={inputClass}
                        />
                    ) : (
                        <span className="text-sm">{link.name}</span>
                    )}
                </div>
            </td>
            <td className={`${tdClass} w-[23%]`}>
                <div className={innerClass}>
                    {isEditing ? (
                        <select
                            value={data.school_group}
                            onChange={(e) => form.setData("school_group", e.target.value)}
                            className={selectClass}
                        >
                            {schoolGroups.map((group) => (
                                <option key={group.value} value={group.value}>
                                    {group.label}
                                </option>
                            ))}
                        </select>
                    ) : (
                        <span className="text-sm">
                            {schoolGroups.find((g) => g.value === link.school_group)?.label ??
                                link.school_group}
                        </span>
                    )}
                </div>
            </td>
            <td className={`${tdClass} w-[10%]`}>
                <div className={innerClass}>
                    {isEditing ? (
                        <select
                            value={data.school_level}
                            onChange={(e) => form.setData("school_level", e.target.value)}
                            className={selectClass}
                        >
                            {schoolLevels.map((level) => (
                                <option key={level.value} value={level.value}>
                                    {level.label}
                                </option>
                            ))}
                        </select>
                    ) : (
                        <span className="text-sm">
                            {schoolLevels.find((l) => l.value === link.school_level)?.label ??
                                link.school_level}
                        </span>
                    )}
                </div>
            </td>
            <td className={`${tdClass} w-[28%]`}>
                <div className={innerClass}>
                    {isEditing ? (
                        <input
                            type="url"
                            value={data.url}
                            onChange={(e) => form.setData("url", e.target.value)}
                            className={inputClass}
                        />
                    ) : (
                        <a
                            href={link.url}
                            target="_blank"
                            rel="noreferrer"
                            className="text-blue-600 text-sm truncate block w-full"
                        >
                            {link.url}
                        </a>
                    )}
                </div>
            </td>
            <td className={`${tdClass} w-[15%]`}>
                <div className={`${innerClass} justify-end gap-x-0`}>
                    {isEditing ? (
                        <>
                            <button
                                onClick={onCancel}
                                className="bg-gray-600 hover:bg-gray-700 text-white p-2 rounded-l-xl flex items-center justify-center cursor-pointer"
                                aria-label="Cancel"
                                title="Cancel"
                                disabled={form.processing}
                            >
                                <RiCloseLine size={20} />
                            </button>
                            <button
                                onClick={() =>
                                    form.put(`/admin/contact-links/${link.id}`, {
                                        onSuccess: () => onCancel(),
                                    })
                                }
                                disabled={form.processing}
                                className="bg-green-500 hover:bg-green-600 text-white p-2 rounded-r-xl flex items-center justify-center cursor-pointer"
                                aria-label="Save"
                                title="Save"
                            >
                                <RiCheckLine size={20} />
                            </button>
                        </>
                    ) : (
                        <>
                            <button
                                onClick={() => onEdit(link)}
                                className="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-l-xl flex items-center justify-center cursor-pointer"
                                aria-label="Edit"
                                title="Edit"
                            >
                                <RiPencilLine size={20} />
                            </button>
                            <button
                                onClick={() => {
                                    if (confirm("Delete this contact link?")) {
                                        onDelete(link.id);
                                    }
                                }}
                                className="bg-red-600 hover:bg-red-500 text-white p-2 rounded-r-xl flex items-center justify-center cursor-pointer"
                                aria-label="Delete"
                                title="Delete"
                            >
                                <RiDeleteBinFill size={20} />
                            </button>
                        </>
                    )}
                </div>
            </td>
        </tr>
    );
}
