<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>لوحة تحكم المساجد</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            width: 100%;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            direction: rtl;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-6" 
      x-data="mosqueDashboard()" 
      x-init="init()">
    
    <!-- Navigation Menu -->
    @include('components.nav')
    
    <div x-show="currentView === 'dashboard'" x-cloak>
        @include('dashboard.dashboard-view')
    </div>

    <div x-show="currentView === 'members'" x-cloak>
        @include('dashboard.members-view')
    </div>

    <div x-show="currentView === 'mosques'" x-cloak>
        @include('dashboard.entity-list-view', ['entityType' => 'mosques', 'title' => 'إدارة المساجد والجوامع'])
    </div>

    <div x-show="currentView === 'provinces'" x-cloak>
        @include('dashboard.entity-list-view', ['entityType' => 'provinces', 'title' => 'إدارة المحافظات'])
    </div>

    <div x-show="currentView === 'neighborhoods'" x-cloak>
        @include('dashboard.entity-list-view', ['entityType' => 'neighborhoods', 'title' => 'إدارة الأحياء'])
    </div>

    <div x-show="currentView === 'housing'" x-cloak>
        @include('dashboard.entity-list-view', ['entityType' => 'housing', 'title' => 'إدارة السكن'])
    </div>

    @include('dashboard.modal')

    <script>
        function mosqueDashboard() {
            return {
                currentView: @json($currentView ?? 'dashboard'),
                selectedEntity: null,
                timeFilter: 'شهري',
                showModal: false,
                modalType: '',
                selectedMember: null,
                formData: {},
                
                entities: {
                    provinces: @json($provinces),
                    neighborhoods: @json($neighborhoods),
                    mosques: @json($mosques),
                    housing: @json($housings),
                    members: @json($members),
                    transferHistory: @json($transferHistory),
                },
                
                documents: @json($documents),
                
                mainStats: [
                    { label: 'عدد الأحياء', value: {{ $mainStats['neighborhoods'] }}, icon: 'MapPin', color: 'bg-blue-500', entity: 'neighborhoods' },
                    { label: 'عدد المحافظات', value: {{ $mainStats['provinces'] }}, icon: 'Building2', color: 'bg-green-500', entity: 'provinces' },
                    { label: 'عدد المساجد', value: {{ $mainStats['mosques'] }}, icon: 'Building2', color: 'bg-purple-500', entity: 'mosques' },
                    { label: 'عدد المنسوبين', value: {{ $mainStats['members'] }}, icon: 'Users', color: 'bg-orange-500', entity: 'members' },
                    { label: 'عدد السكن', value: {{ $mainStats['housings'] }}, icon: 'Briefcase', color: 'bg-teal-500', entity: 'housing' },
                    { label: 'عدد الفئات', value: {{ $mainStats['categories'] }}, icon: 'Tag', color: 'bg-indigo-500', entity: 'categories' },
                    { label: 'عدد المهن', value: {{ $mainStats['professions'] }}, icon: 'Briefcase', color: 'bg-pink-500', entity: 'professions' }
                ],
                
                init() {
                    // Initialize component
                },
                
                getRouteUrl(entity) {
                    const routes = {
                        'dashboard': '{{ route('dashboard') }}',
                        'members': '{{ route('members.index') }}',
                        'mosques': '{{ route('mosques.index') }}',
                        'provinces': '{{ route('provinces.index') }}',
                        'neighborhoods': '{{ route('neighborhoods.index') }}',
                        'housing': '{{ route('housing.index') }}',
                        'categories': '{{ route('categories.index') }}',
                        'professions': '{{ route('professions.index') }}'
                    };
                    return routes[entity] || '{{ route('dashboard') }}';
                },
                
                navigateToRoute(entity) {
                    window.location.href = this.getRouteUrl(entity);
                },
                
                openModal(type, entity = null, entityType = null) {
                    this.modalType = type;
                    this.selectedEntity = entity;
                    if (entity) {
                        this.formData = { ...entity };
                    } else {
                        this.formData = {};
                    }
                    this.showModal = true;
                },
                
                closeModal() {
                    this.showModal = false;
                    this.formData = {};
                    this.selectedEntity = null;
                    this.selectedMember = null;
                },
                
                handleDelete(entityType, id) {
                    if (confirm('هل أنت متأكد من الحذف؟')) {
                        this.entities[entityType] = this.entities[entityType].filter(item => item.id !== id);
                        this.closeModal();
                    }
                },
                
                handleTransfer(member) {
                    this.selectedMember = member;
                    this.modalType = 'transfer';
                    this.showModal = true;
                },
                
                handleCategoryChange(member) {
                    this.selectedMember = member;
                    this.modalType = 'changeCategory';
                    this.formData = {
                        newCategory: '',
                        reason: ''
                    };
                    this.showModal = true;
                },
                
                handleCategoryChangeSubmit(event) {
                    event.preventDefault();
                    const formData = {
                        newCategory: this.formData.newCategory,
                        reason: this.formData.reason
                    };
                    this.submitCategoryChange(formData);
                },
                
                viewTransferHistory(member) {
                    this.selectedMember = member;
                    this.modalType = 'transferHistory';
                    this.showModal = true;
                },
                
                submitTransfer(data) {
                    const memberMosque = this.selectedMember.mosque?.name || this.selectedMember.mosque || '';
                    const newTransfer = {
                        id: (this.entities.transferHistory?.length || 0) + 1,
                        memberId: this.selectedMember.id,
                        memberName: this.selectedMember.name,
                        fromMosque: memberMosque,
                        toMosque: data.toMosque,
                        transferDate: new Date().toISOString().split('T')[0],
                        transferredBy: 'مدير النظام',
                        reason: data.reason,
                        oldCategory: this.selectedMember.category,
                        newCategory: this.selectedMember.category
                    };
                    
                    if (!this.entities.transferHistory) {
                        this.entities.transferHistory = [];
                    }
                    this.entities.transferHistory.push(newTransfer);
                    
                    this.entities.members = this.entities.members.map(m =>
                        m.id === this.selectedMember.id
                            ? { ...m, mosque: data.toMosque }
                            : m
                    );
                    
                    alert('تم النقل بنجاح');
                    this.closeModal();
                },
                
                handleTransferSubmit(event) {
                    event.preventDefault();
                    const formData = {
                        toMosque: event.target.elements.toMosque.value,
                        reason: event.target.elements.reason.value
                    };
                    this.submitTransfer(formData);
                },
                
                submitCategoryChange(data) {
                    const memberMosque = this.selectedMember.mosque?.name || this.selectedMember.mosque || '';
                    const transfer = {
                        id: this.entities.transferHistory.length + 1,
                        memberId: this.selectedMember.id,
                        memberName: this.selectedMember.name,
                        fromMosque: memberMosque,
                        toMosque: memberMosque,
                        transferDate: new Date().toISOString().split('T')[0],
                        transferredBy: 'مدير النظام',
                        reason: `تغيير الفئة: ${data.reason}`,
                        oldCategory: this.selectedMember.category,
                        newCategory: data.newCategory
                    };
                    
                    if (!this.entities.transferHistory) {
                        this.entities.transferHistory = [];
                    }
                    this.entities.transferHistory.push(transfer);
                    
                    this.entities.members = this.entities.members.map(m =>
                        m.id === this.selectedMember.id
                            ? { ...m, category: data.newCategory }
                            : m
                    );
                    
                    alert('تم تغيير الفئة بنجاح');
                    this.closeModal();
                },
                
                submitMemberForm(member) {
                    if (this.modalType === 'add') {
                        const newMember = {
                            ...member,
                            id: this.entities.members.length + 1,
                            mosqueId: this.entities.mosques.find(m => m.name === member.mosque)?.id || null,
                            housingId: member.housing ? this.entities.housing.find(h => h.name === member.housing)?.id || null : null
                        };
                        this.entities.members.push(newMember);
                        alert('تم إضافة المنسوب بنجاح');
                    } else if (this.modalType === 'edit') {
                        this.entities.members = this.entities.members.map(m =>
                            m.id === this.selectedEntity.id
                                ? { ...m, ...member }
                                : m
                        );
                        alert('تم تحديث بيانات المنسوب بنجاح');
                    }
                    this.closeModal();
                },
                
                handleDocumentUpload(document, file) {
                    this.documents.push(document);
                    alert('تم رفع الوثيقة بنجاح');
                },
                
                handleDocumentDownload(document) {
                    alert(`جارٍ تحميل الوثيقة: ${document.documentName}`);
                },
                
                handleDocumentDelete(documentId) {
                    if (confirm('هل أنت متأكد من حذف هذه الوثيقة؟')) {
                        this.documents = this.documents.filter(doc => doc.id !== documentId);
                        alert('تم حذف الوثيقة بنجاح');
                    }
                }
            }
        }
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>

